<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SsoAccessTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the sso_access_tokens table with initial data.
     */
    public function run()
    {
        DB::table('sso_access_tokens')->insert([
            [
                'id' => '1uNnZlaLQuFIDDV5cCLqfSADFEfST7TqQMzSpuJzUuW1RVl7y3CXDV0S1jHd',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:42:29',
                'created_at' => '2026-01-11 07:42:29',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => '2eo7KACgBVg92SeEavGqqgRaILmI4ZEtU4rcfbzQvlzmwMOIjOF4Mgqq5oww',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-23 00:43:21',
                'created_at' => '2026-01-08 00:43:21',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => '2XdtIcCp7blvC5VGtJFta3t7ikqTlYVa7AzfGo10AwGp3zmlo7yg3r027y7m',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:08:36',
                'created_at' => '2026-01-07 14:08:36',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => '3H0O7UEp2YiJteCkeVNem9K9E7DYQvGF5tMXrcVrwMfaJyRrdhKw2X3IS7u3',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 03:08:09',
                'created_at' => '2026-01-16 03:08:09',
                'updated_at' => '2026-01-16 03:08:09'
            ],
            [
                'id' => '4IEwIpFCd4emKNVodM3kp8INyJsCaBdFswsoDFq1JJ3yP4WpPG5rqdVQSuA3',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-23 00:43:13',
                'created_at' => '2026-01-08 00:43:13',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => '52ZKfLw1sqa6MiQHbxhqk7Vx9SDyr4dRtjBmDl1gOrnuVZcaYrVmdzANMLjE',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:07:22',
                'created_at' => '2026-01-07 14:07:22',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => '5vQjf0oqAtBaIGsDKuYQabEzEaYW1BkDsDQTu37VaC1XIzlkUbddD8sAhx2C',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 03:56:43',
                'created_at' => '2026-01-16 03:56:43',
                'updated_at' => '2026-01-16 03:56:43'
            ],
            [
                'id' => '70NKlQcHkptpC0hrl0RevYxW8r4PkXKLaznOM30cHLvjmsDe8hHFrGvXYhye',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 04:03:28',
                'created_at' => '2026-01-16 04:03:28',
                'updated_at' => '2026-01-16 04:03:28'
            ],
            [
                'id' => '7BDBJUEmLdTG8HiF5JfdGH6eYn6nm6XmSeTwFIPO8TZpytSYFQuRX3pTDR7K',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 04:03:32',
                'created_at' => '2026-01-16 04:03:32',
                'updated_at' => '2026-01-16 04:03:32'
            ],
            [
                'id' => '7EeXJ25cQa4CBhoOtYzKtUYmAgMF2kLDv3TImDNQbPEXnCeSZhaxhX04zK8i',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:42:10',
                'created_at' => '2026-01-11 07:42:10',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => '7Y6HOywH1Zs0tLlyXgHyeWvxpcDkyCEwGoofPGJ9Qwxa9JkubJAXvhmS9sVC',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-30 07:01:12',
                'created_at' => '2026-01-15 07:01:12',
                'updated_at' => '2026-01-15 07:01:12'
            ],
            [
                'id' => '8hPsZndO44nZPo7yWHg6FE6YjjHafiI2gQWllWuRFI95zgAUOXNH5hGEDvsv',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:41:28',
                'created_at' => '2026-01-11 07:41:28',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'A7IRIY36YyPRunYZLBDVVhVUyC0g650kzZmyJ8pyt9re3Er2MGbDtmcjYHme',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-29 10:22:25',
                'created_at' => '2026-01-14 10:22:25',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'AG3uqVTRWfkPf7xe6PiZSt4iABPtNVsCfjcGFjZB8JuQgPzFPaKoV6OexfP6',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-23 00:47:50',
                'created_at' => '2026-01-08 00:47:50',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'ALuEjeb6IbpM4TpNdtMQufkiDFMu5S8qOcvrqd9q1fEPryQYx6gs6UGhSKJ3',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:40:24',
                'created_at' => '2026-01-11 07:40:24',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'C3YhQ1srOqGTLBNCVTzNFtsMCQF4YIfHd1Yd0XBY8j79p9s3CZNE5scmVbJR',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 03:06:43',
                'created_at' => '2026-01-16 03:06:43',
                'updated_at' => '2026-01-16 03:06:43'
            ],
            [
                'id' => 'cHGMMDXcwRW1FNYsvsI2oiztcS4wB75oSsOIrCJn4KxpXw8wOL2qatDyohTN',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:09:16',
                'created_at' => '2026-01-07 14:09:16',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'cUmedaB0B5Gu5vSbmqpJ6eNlixhVrNm3cKeTX8CdbftLGoqh35LiNgzCJ24q',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:59:40',
                'created_at' => '2026-01-11 07:59:40',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'DUFvuH7bRhq0b2pyulMH2pWavNZYLpg2oY7npJndoMKOFKHd7uvELfO8hl75',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-27 03:54:10',
                'created_at' => '2026-01-12 03:54:10',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'gNIWYme86rjsfoU2E7TLBRgiXEqOKboVt9M5oVIjDf529jWn1Qvs5m7OROKu',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-30 07:00:28',
                'created_at' => '2026-01-15 07:00:28',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'gwyOomkoyJ5bebuu3I7FsYDqlJar72SA8TuN7fjtCeNA3WHIK4GBQYvGg4HU',
                'client_id' => 2,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email',
                'revoked' => 0,
                'expires_at' => '2026-01-30 07:27:13',
                'created_at' => '2026-01-15 07:27:13',
                'updated_at' => '2026-01-15 07:27:13'
            ],
            [
                'id' => 'I5LE1UzvW6Nl0E5keqxfzLR4V95uuxxHB9gvkfBxhkMH1q65TIYeM10PXXNB',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:41:56',
                'created_at' => '2026-01-11 07:41:56',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'InOz4cmDfz1rNjnujTQS7Zq1tJjAvyZHGHjkz0OGBCxQ013aHPpFnuKDJhux',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 03:13:49',
                'created_at' => '2026-01-16 03:13:49',
                'updated_at' => '2026-01-16 03:13:49'
            ],
            [
                'id' => 'jg0rPxOjWJpY65lyvDZskCnpS486Y2fxrOsWjB8or5dELtORhGp4sz9Oovxb',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 03:12:28',
                'created_at' => '2026-01-16 03:12:28',
                'updated_at' => '2026-01-16 03:12:28'
            ],
            [
                'id' => 'JMuOzyAz8xVKkWjUBg8fY0IBUKfrNNmz7YvjuPjjLMYUGjJGNSVrCZUOLuZH',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:12:59',
                'created_at' => '2026-01-07 14:12:59',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'JTKmjaDLLCiPO1mEXRiRGRsyxbXa0Xe1u7Sx9CU7ckYXtKxCWWUrjyAHTffB',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 04:12:20',
                'created_at' => '2026-01-16 04:12:20',
                'updated_at' => '2026-01-16 04:12:20'
            ],
            [
                'id' => 'JUhOwwJMhfk5IwFudvw0aj2SeHt2M7dKkwzsOpISgb70Ina0ByNrdm6TCNBm',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-02-01 08:12:23',
                'created_at' => '2026-01-17 08:12:23',
                'updated_at' => '2026-01-17 08:12:23'
            ],
            [
                'id' => 'JxibBZt2uivSm2Zqe5Xhv7owcA4b3GATkI0PDALGP28zGHt3XNBenSZiO3iQ',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:06:32',
                'created_at' => '2026-01-07 14:06:32',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'K0e5qPago9b4vTSEOJOouxQNhBcCmaUVD0mR2Axhw0XY0OWp518TSqnj6pUi',
                'client_id' => 2,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-30 07:25:44',
                'created_at' => '2026-01-15 07:25:44',
                'updated_at' => '2026-01-15 07:25:44'
            ],
            [
                'id' => 'KR1wf4M6xDsnnIwp1TUeJicFXv5pqEn2unfy4yQtA26d77GavLJs5fwSHacr',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:07:11',
                'created_at' => '2026-01-07 14:07:11',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'kRLJg3YcNlrSMAs7kkIm6cfzhGtCdALve7jXkMsYtYeYvGGVXlvMaYAmRFHi',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:42:23',
                'created_at' => '2026-01-11 07:42:23',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'LgWxHoyl503yhqLJ7dzoC1lpWCCCgxSaCvd6Avh6oRSNF1moNqT45AjfCtq4',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 04:08:57',
                'created_at' => '2026-01-16 04:08:57',
                'updated_at' => '2026-01-16 04:08:57'
            ],
            [
                'id' => 'LKvtPov0oYSW88szNUB2vOUteNxcZm64un888rg22EAhFOKJf2rhF6tfHEyj',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 04:01:24',
                'created_at' => '2026-01-16 04:01:24',
                'updated_at' => '2026-01-16 04:01:24'
            ],
            [
                'id' => 'lv4VTB4nH41nM45qRDpj4wMy10ZmtQcmD44tENpafjg0zMu6O7jfCJ73z8eR',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 15:23:21',
                'created_at' => '2026-01-16 15:23:21',
                'updated_at' => '2026-01-16 15:23:21'
            ],
            [
                'id' => 'lwK47ElFGoMSyzD3AWDxohhISK37ULvQ8St3rIqAFkTneovSwv6nFGF2hJvK',
                'client_id' => 2,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email',
                'revoked' => 0,
                'expires_at' => '2026-01-30 07:27:22',
                'created_at' => '2026-01-15 07:27:22',
                'updated_at' => '2026-01-15 07:27:22'
            ],
            [
                'id' => 'lwZzVLDCxia7aw8LeacfI6TKjf9oVpMT6Sh5QcyTCXoNXrdsbyA3o8pRC3Nb',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-23 00:43:06',
                'created_at' => '2026-01-08 00:43:06',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'nOm8GKNBL35PdyGy60rwQUfnr3ZOuXsDp8KXHAcvakZOsRxZmreJyyzsAuOd',
                'client_id' => 2,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:17:46',
                'created_at' => '2026-01-07 14:17:46',
                'updated_at' => '2026-01-15 07:01:01'
            ],
            [
                'id' => 'OuGgPSLiVSwt1zolzBEQNDNRzHT34Nb6xFtquUj0Sp1FpBJZo6Ob2OX7p0zV',
                'client_id' => 2,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:12:46',
                'created_at' => '2026-01-07 14:12:46',
                'updated_at' => '2026-01-15 07:01:01'
            ],
            [
                'id' => 'pk3k2rRjYZ6jOw1PdfbnnQPKgMAcifWxu0UVrHlMyUWaOzjzagfLXn8FcQZ8',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-30 07:01:20',
                'created_at' => '2026-01-15 07:01:20',
                'updated_at' => '2026-01-15 07:01:20'
            ],
            [
                'id' => 's6uHLwuA2cPKo2tcrGD4Fs08ZHmjOuR6vYxLvxqJhcc7kRqsFVDMzo0PdVuB',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-30 07:29:16',
                'created_at' => '2026-01-15 07:29:16',
                'updated_at' => '2026-01-15 07:29:16'
            ],
            [
                'id' => 'svjIOqP8cS9TFip3TY7GIs1JFy6FsS61GR1iuATvxFuncmmb9RsXmd1ClIba',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-27 03:53:00',
                'created_at' => '2026-01-12 03:53:00',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'TjWOGyXiKWTqcBHQ5pdIP6hPAAgv5BiW4v9L3umBVckibKmqa2v8CJFoYv26',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-26 07:41:20',
                'created_at' => '2026-01-11 07:41:20',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'TlRn7EbEbny27ithL7VwpNcaUlMjvVexLeNV4L8ikWYaktg8Bx2PhADld8wg',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-23 00:44:28',
                'created_at' => '2026-01-08 00:44:28',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'trB0GGOmeGDoDbcSgrSMMANw8uH8LLW8c0UiAnBDyCNKWFFNlNspEI20dK1y',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:11:22',
                'created_at' => '2026-01-07 14:11:22',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'u9S1zYDpmIzTBphXKX6Yp92Xu9I5hhhUaDJx3qZsRgiz41KKxRRRz8EkDXzj',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-30 04:31:30',
                'created_at' => '2026-01-15 04:31:30',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'uel5UnXhVdEJoRvEZLpQzKPQJ5HWh0n4771i30DSwk7kyqfT3rtUXPZ2NITp',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:08:54',
                'created_at' => '2026-01-07 14:08:54',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'ufyVzOUr8bQSnM0TFasJQO8O2uWR781HJOpQr5NxcAZEHT8XCsZHFdiJFEpB',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:12:51',
                'created_at' => '2026-01-07 14:12:51',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'vd54VD9OEZR8OKlPsfTn4lYfIrAR8Zn6XrFzrfNaECO4seYugaoAEwYnxQnh',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 15:13:15',
                'created_at' => '2026-01-16 15:13:15',
                'updated_at' => '2026-01-16 15:13:15'
            ],
            [
                'id' => 'VdYIicwndclsxEiiiIKDQAD9TRr7hPO6qrlWo0imNHp3JUKPnNGJDt8bgCgP',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 0,
                'expires_at' => '2026-01-31 03:56:14',
                'created_at' => '2026-01-16 03:56:14',
                'updated_at' => '2026-01-16 03:56:14'
            ],
            [
                'id' => 'vlLlJpOCQborZKahG67ZSnNx8GHEEjrXM5GEiYR5JdNXY2FReTCrD5IPHCxm',
                'client_id' => 2,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:12:22',
                'created_at' => '2026-01-07 14:12:22',
                'updated_at' => '2026-01-15 07:01:01'
            ],
            [
                'id' => 'VOPmTHE1SSqcpJySefrRI4YfOn45vRLwMg57c5WAOSLnVhZ4bRsy24lyDuNb',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-28 07:04:53',
                'created_at' => '2026-01-13 07:04:53',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'wnoKacfpPC44R7mvZ4sR3MFXux4xg9fTupvZvl42j3dD3zZTNeufJWuQUzNp',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:07:43',
                'created_at' => '2026-01-07 14:07:43',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'XeceOZbrh9p2MxLA6B73T123xA9iOjiltUnnUsBwkAeApPgTzHEq1386vkpB',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:06:25',
                'created_at' => '2026-01-07 14:06:25',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'xeOYUMmLssTNTaRROzZ8GiwwH6lARZP0MFyLFw2YGlNt9NSX3Fm2rRqDe3zm',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-23 00:43:16',
                'created_at' => '2026-01-08 00:43:16',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'xXPo42veUqeSv9eoj7NSroB3cvKMdF43XYyHuTRMqASxID9wulGwDzPeOhZp',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:06:42',
                'created_at' => '2026-01-07 14:06:42',
                'updated_at' => '2026-01-15 07:00:57'
            ],
            [
                'id' => 'y8avIL5x1kixh1Y4bYtpOrLCaZNUEOQ8w1LeAEKgZ29I6h1pWwgpfjAWQc3L',
                'client_id' => 1,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'scopes' => 'openid email profile',
                'revoked' => 1,
                'expires_at' => '2026-01-22 14:06:29',
                'created_at' => '2026-01-07 14:06:29',
                'updated_at' => '2026-01-15 07:00:57'
            ]
        ]);
    }
}