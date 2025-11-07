<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116082508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_schedule CHANGE session_type session_type enum(\'Morning\', \'Evening\', \'Afternoon\')');
        $this->addSql('ALTER TABLE member_package CHANGE status status enum( \'active\', \'cancel\', \'expired\')');
        $this->addSql('ALTER TABLE corporate_member_email CHANGE status status enum( \'pending\', \'sent\',\'failed\')');
        $this->addSql('ALTER TABLE subscription_billing CHANGE transaction_type transaction_type enum(\'Payment\', \'Refund\', \'Cancellation\')');
        $this->addSql('ALTER TABLE sections CHANGE type type enum(\'list\', \'rect\', \'box\'), CHANGE direction direction enum(\'horizontal\', \'vertical\')');
        $this->addSql('ALTER TABLE member_schedule_activity CHANGE status status enum( \'booked\', \'favourite\', \'cancelled\', \'expired\',\'reserved\')');
        $this->addSql('ALTER TABLE app_version CHANGE device_type device_type enum( \'iphone\', \'android\')');
        $this->addSql('ALTER TABLE subscription CHANGE product_type product_type enum( \'package\', \'challenge\')');
        $this->addSql('ALTER TABLE user_bank_info CHANGE currency_code currency_code enum(\'AED\', \'GBP\', \'USD\')');
        $this->addSql('ALTER TABLE member_billing CHANGE transaction_type transaction_type enum(\'Payment\', \'Refund\', \'Cancellation\')');
        $this->addSql('ALTER TABLE device_config CHANGE lang lang  enum(\'en\',\'ar\',\'de\') ');
        $this->addSql('ALTER TABLE corporate_key CHANGE type type enum(\'Discount\', \'Package\', \'CorePass\', \'Profile\', \'Default\',\'Referral\')');
        $this->addSql('ALTER TABLE schedule_detail CHANGE recurrence recurrence enum(\'Monday\', \'Tuesday\', \'Wednesday\', \'Thursday\', \'Friday\', \'Saturday\', \'Sunday\'), CHANGE session_type session_type enum(\'Morning\', \'Evening\', \'Afternoon\')');
        $this->addSql('ALTER TABLE app_setting CHANGE device_type device_type enum(\'android\',\'ios\')');
        $this->addSql('ALTER TABLE member_invitation CHANGE invitation_status invitation_status enum(\'enqueue\',\'pending\',\'confirmed\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_schedule CHANGE session_type session_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE app_setting CHANGE device_type device_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE app_version CHANGE device_type device_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE corporate_key CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE corporate_member_email CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE device_config CHANGE lang lang VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE member_billing CHANGE transaction_type transaction_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE member_invitation CHANGE invitation_status invitation_status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE member_package CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE member_schedule_activity CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE schedule_detail CHANGE recurrence recurrence VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE session_type session_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE sections CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE direction direction VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE subscription CHANGE product_type product_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE subscription_billing CHANGE transaction_type transaction_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_bank_info CHANGE currency_code currency_code VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
