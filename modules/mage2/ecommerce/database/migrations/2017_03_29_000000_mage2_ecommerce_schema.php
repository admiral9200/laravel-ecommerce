<?php
/**
 * Mage2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to ind.purvesh@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://mage2.website for more information.
 *
 * @author    Purvesh <ind.purvesh@gmail.com>
 * @copyright 2016-2017 Mage2
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mage2\Ecommerce\Models\Database\Configuration;

class Mage2EcommerceSchema extends Migration
{

    /**
     * Install the Mage2 Address Module Schema.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_super_admin')->nullable();
            $table->integer('role_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('language')->nullable()->default('en');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image_path')->nullable();
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['GUEST', 'LIVE'])->default('LIVE');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->integer('user_id');
            $table->integer('client_id');
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->integer('user_id')->index()->nullable();
            $table->integer('client_id');
            $table->string('name')->nullable();
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->timestamps();
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('access_token_id', 100)->index();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->nullable();
            $table->string('name');
            $table->string('secret', 100);
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();

            //$table->foreign('user_id')->references('id')->on('admin_users')->onDelete('cascade');
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('type', ['SHIPPING', 'BILLING']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address1');
            $table->string('address2');
            $table->string('postcode');
            $table->string('city');
            $table->string('state');
            $table->integer('country_id')->unsigned();
            $table->string('phone');
            $table->timestamps();
        });

        Schema::create('gift_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->float('discount', 6, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['ENABLED', 'DISABLED']);
            $table->timestamps();
        });

        Schema::create('configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('configuration_key')->nullable()->default(null);
            $table->string('configuration_value')->nullable()->default(null);
            $table->timestamps();
        });



        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->index();
            $table->timestamps();
        });

        Schema::table('user_viewed_products', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        //addresses table foreign key setup
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        //reviews table foreign key setup
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });


        //orders table foreign key setup
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Configuration::create(['configuration_key' => 'general_site_title', 'configuration_value' => 'Mage2 Laravel Ecommerce']);
        Configuration::create(['configuration_key' => 'general_site_description', 'configuration_value' => 'Mage2 Laravel Ecommerce']);

    }

    /**
     * Uninstall the Mage2 Address Module Schema.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('oauth_personal_access_clients');
        Schema::drop('oauth_clients');
        Schema::drop('oauth_refresh_tokens');
        Schema::drop('oauth_access_tokens');
        Schema::drop('oauth_auth_codes');

        Schema::drop('admin_password_resets');
        Schema::drop('admin_users');
        Schema::drop('password_resets');
        Schema::drop('users');
        Schema::drop('addresses');
        Schema::drop('configurations');
        Schema::dropIfExits('gift_coupons');

    }

}
