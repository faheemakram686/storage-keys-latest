<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Contacts are soft-deleted (is_deleted = 1), but contacts.email had a hard
 * UNIQUE index, so the email of a deleted contact could never be used again
 * ("Duplicate entry ... for key contacts_email_unique").
 *
 * Uniqueness now only applies to live contacts: a generated column holds the
 * email while is_deleted = 0 and NULL otherwise (NULLs never clash), and the
 * unique index sits on that column.
 */
return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE contacts ADD COLUMN active_email VARCHAR(255)
            GENERATED ALWAYS AS (IF(is_deleted = 0, email, NULL)) STORED');
        DB::statement('ALTER TABLE contacts ADD UNIQUE INDEX contacts_active_email_unique (active_email)');
        DB::statement('ALTER TABLE contacts DROP INDEX contacts_email_unique');
        DB::statement('ALTER TABLE contacts ADD INDEX contacts_email_index (email)');
    }

    public function down()
    {
        // Restoring the strict index fails while a deleted and a live contact share an email.
        DB::statement('ALTER TABLE contacts DROP INDEX contacts_email_index');
        DB::statement('ALTER TABLE contacts ADD UNIQUE INDEX contacts_email_unique (email)');
        DB::statement('ALTER TABLE contacts DROP INDEX contacts_active_email_unique');
        DB::statement('ALTER TABLE contacts DROP COLUMN active_email');
    }
};
