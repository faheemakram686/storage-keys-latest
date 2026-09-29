<?php

namespace App\Http\Controllers\Tenant\Attendance;

use App\Filters\Tenant\AttendanceRequestsFilter;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Attendance\Attendance;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;

class AttendanceRequestController extends Controller
{
    public function __construct(AttendanceRequestsFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index(Request $request)
    {
        $attendanceStatuses = resolve(StatusRepository::class)
            ->attendance('status_pending', 'status_approve', 'status_reject');

        return Attendance::filters($this->filter)->with([
            'user:id,first_name,last_name,status_id',
            'user.department:id,name',
            'user.profilePicture',
            'user.status:id,name,class',
            'details' => fn(HasMany $hasMany) => $hasMany
                ->select('id', 'in_time', 'out_time', 'attendance_id', 'status_id', 'review_by', 'attendance_details_id')
                ->orderBy('id', 'DESC')
                ->whereIn('status_id', array_keys($attendanceStatuses)),
            'details.comments' => fn(MorphMany $many) => $many->orderBy('parent_id', 'DESC')
                ->select('id', 'commentable_type', 'commentable_id', 'user_id', 'type', 'comment', 'parent_id'),
            'details.status:id,name,class',
        ])->whereHas ('details', fn(Builder $query) => $query
                ->when(request()->has('rejected') && request()->get('rejected') == "true",
                    fn(Builder $query) => $query
                        ->where('status_id', array_search('status_reject', $attendanceStatuses)),
                    fn(Builder $query) => $query
                        ->where('status_id', array_search('status_pending', $attendanceStatuses)),
                )
        )->latest()
        ->paginate(request()->get('per_page', 10));

    }
    public function getRequestsApi(Request $request)
    {
        try {
            $attendanceStatuses = resolve(StatusRepository::class)
                ->attendance('status_pending', 'status_approve', 'status_reject');

            $pendingId = array_search('status_pending', $attendanceStatuses, true);
            $approveId = array_search('status_approve', $attendanceStatuses, true);
            $rejectId = array_search('status_reject', $attendanceStatuses, true);

            // Mobile: rejected=true → only rejected; otherwise show pending + approved + rejected
            // (admins often auto-approve their own request, so "pending only" looked empty).
            $rejectedOnly = $request->boolean('rejected')
                || (string) $request->query('rejected') === 'true';

            $detailStatusIds = $rejectedOnly
                ? array_values(array_filter([$rejectId]))
                : array_values(array_filter([$pendingId, $approveId, $rejectId]));

            $userId = (int) auth()->id();

            $data = Attendance::query()
                ->with([
                    'user:id,first_name,last_name,status_id',
                    'user.department:id,name',
                    'user.profilePicture',
                    'user.status:id,name,class',
                    'details' => fn (HasMany $hasMany) => $hasMany
                        ->select('id', 'in_time', 'out_time', 'attendance_id', 'status_id', 'review_by', 'added_by', 'attendance_details_id', 'created_at')
                        ->orderBy('id', 'DESC')
                        ->whereIn('status_id', $detailStatusIds)
                        // Manual / attendance-request punches always set added_by.
                        ->whereNotNull('added_by'),
                    'details.comments' => fn (MorphMany $many) => $many->orderBy('parent_id', 'DESC')
                        ->select('id', 'commentable_type', 'commentable_id', 'user_id', 'type', 'comment', 'parent_id'),
                    'details.status:id,name,class',
                ])
                ->where('user_id', $userId)
                ->whereHas('details', function (Builder $query) use ($detailStatusIds) {
                    $query->whereIn('status_id', $detailStatusIds)
                        ->whereNotNull('added_by');
                })
                ->latest('in_date')
                ->latest('id')
                ->limit(min(100, max(1, (int) $request->query('limit', 50))))
                ->get();

            return response()->json([
                'status' => true,
                'message' => '',
                'data' => $data,
                'count' => $data->count(),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
