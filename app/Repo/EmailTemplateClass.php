<?php
namespace App\Repo;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\LeadStatus;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\EmailTemplateInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use Illuminate\Http\Request;

class EmailTemplateClass implements EmailTemplateInterface {

    public function saveEmailTemplate($request)
    {
        $temp=new EmailTemplate();
        $temp->temp_name = $request->temp_name;
        $temp->temp_for = $request->temp_for;
        $temp->temp_cat = $request->temp_category;
        $temp->temp_subject = $request->temp_subject;
        $temp->temp_body = $request->temp_body;
        $temp->status=$request->status;
        if($temp->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllEmailTemplate()
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getEmailTemplate($request)
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('temp_for','=', $request);
        $qry=$qry->where('temp_cat','=', 'email');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getTemplate($for,$type)
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('temp_for','=', $for);
        $qry=$qry->where('temp_cat','=', $type);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getTemplateByName($for,$type,$name)
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('temp_for','=', $for);
        $qry=$qry->where('temp_cat','=', $type);
        $qry=$qry->where('temp_name','=', $name);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteEmailTemplate($id)
    {
        $country=EmailTemplate::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editEmailTemplate($id)
    {
        return $country=EmailTemplate::find($id);
    }

    public function updateEmailTemplate($request)
    {

        $temp = EmailTemplate::find($request->id);
        $temp->temp_name = $request->et_temp_name;
        $temp->temp_for = $request->et_temp_for;
        $temp->temp_cat = $request->et_temp_category;
        $temp->temp_subject = $request->et_temp_subject;
        $temp->temp_body = $request->et_temp_body;
        $temp->status=$request->edit_status;
        $temp->save();
        return 1;
    }

    public function applyTemplateVariables(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $content = str_replace('{{'.$key.'}}', $this->formatTemplateValue($value), $content);
        }

        return $content;
    }

    public function formatTemplateValue($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        return '';
    }

    /**
     * Build common email placeholder map from a recipient model/array.
     */
    public function buildRecipientVariables($recipient): array
    {
        if (is_object($recipient) && method_exists($recipient, 'toArray')) {
            $data = $recipient->toArray();
        } elseif (is_array($recipient)) {
            $data = $recipient;
        } else {
            $data = [];
        }

        return [
            'f_name' => $data['f_name'] ?? $data['first_name'] ?? '',
            'l_name' => $data['l_name'] ?? $data['last_name'] ?? '',
            'company_name' => $data['company_name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'country' => $data['country'] ?? '',
        ];
    }
}
