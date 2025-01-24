<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtpVerificationNpinIpAddressToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add otp_verification field (0 = OTP not sent, 1 = OTP not verified, 2 = OTP verified)
            $table->tinyInteger('otp_verification')->default(0)->comment('0:otp not sent, 1:otp not veridied,2:otp verified')->after('email');

            // Add npin field to store the NPIN
            $table->string('npin')->nullable()->after('otp_verification');

            // Add ip_address field to store the user's IP address
            $table->string('ip_address')->nullable()->after('npin');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the newly added fields
            $table->dropColumn(['otp_verification', 'npin', 'ip_address']);
        });
    }
}
