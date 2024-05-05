<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505104633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_choice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE quiz_answer (id INT NOT NULL, question_id INT DEFAULT NULL, result_id INT DEFAULT NULL, choice_order JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3799BA7C1E27F6BF ON quiz_answer (question_id)');
        $this->addSql('CREATE INDEX IDX_3799BA7C7A7B643 ON quiz_answer (result_id)');
        $this->addSql('CREATE TABLE quiz_answer_quiz_choice (quiz_answer_id INT NOT NULL, quiz_choice_id INT NOT NULL, PRIMARY KEY(quiz_answer_id, quiz_choice_id))');
        $this->addSql('CREATE INDEX IDX_99D8FC7FAC5339E1 ON quiz_answer_quiz_choice (quiz_answer_id)');
        $this->addSql('CREATE INDEX IDX_99D8FC7F9FE61737 ON quiz_answer_quiz_choice (quiz_choice_id)');
        $this->addSql('CREATE TABLE quiz_choice (id INT NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE quiz_question (id INT NOT NULL, quiz_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, choice_order JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6033B00B853CD175 ON quiz_question (quiz_id)');
        $this->addSql('CREATE TABLE quiz_question_choices (quiz_question_id INT NOT NULL, quiz_choice_id INT NOT NULL, PRIMARY KEY(quiz_question_id, quiz_choice_id))');
        $this->addSql('CREATE INDEX IDX_174526FC3101E51F ON quiz_question_choices (quiz_question_id)');
        $this->addSql('CREATE INDEX IDX_174526FC9FE61737 ON quiz_question_choices (quiz_choice_id)');
        $this->addSql('CREATE TABLE quiz_question_correct_choices (quiz_question_id INT NOT NULL, quiz_choice_id INT NOT NULL, PRIMARY KEY(quiz_question_id, quiz_choice_id))');
        $this->addSql('CREATE INDEX IDX_92DC58F13101E51F ON quiz_question_correct_choices (quiz_question_id)');
        $this->addSql('CREATE INDEX IDX_92DC58F19FE61737 ON quiz_question_correct_choices (quiz_choice_id)');
        $this->addSql('CREATE TABLE quiz_result (id INT NOT NULL, quiz_id INT DEFAULT NULL, finished BOOLEAN NOT NULL, question_order JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE2E314A853CD175 ON quiz_result (quiz_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE quiz_answer ADD CONSTRAINT FK_3799BA7C1E27F6BF FOREIGN KEY (question_id) REFERENCES quiz_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_answer ADD CONSTRAINT FK_3799BA7C7A7B643 FOREIGN KEY (result_id) REFERENCES quiz_result (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_answer_quiz_choice ADD CONSTRAINT FK_99D8FC7FAC5339E1 FOREIGN KEY (quiz_answer_id) REFERENCES quiz_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_answer_quiz_choice ADD CONSTRAINT FK_99D8FC7F9FE61737 FOREIGN KEY (quiz_choice_id) REFERENCES quiz_choice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_question_choices ADD CONSTRAINT FK_174526FC3101E51F FOREIGN KEY (quiz_question_id) REFERENCES quiz_question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_question_choices ADD CONSTRAINT FK_174526FC9FE61737 FOREIGN KEY (quiz_choice_id) REFERENCES quiz_choice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_question_correct_choices ADD CONSTRAINT FK_92DC58F13101E51F FOREIGN KEY (quiz_question_id) REFERENCES quiz_question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_question_correct_choices ADD CONSTRAINT FK_92DC58F19FE61737 FOREIGN KEY (quiz_choice_id) REFERENCES quiz_choice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_result ADD CONSTRAINT FK_FE2E314A853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quiz_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_choice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_result_id_seq CASCADE');
        $this->addSql('ALTER TABLE quiz_answer DROP CONSTRAINT FK_3799BA7C1E27F6BF');
        $this->addSql('ALTER TABLE quiz_answer DROP CONSTRAINT FK_3799BA7C7A7B643');
        $this->addSql('ALTER TABLE quiz_answer_quiz_choice DROP CONSTRAINT FK_99D8FC7FAC5339E1');
        $this->addSql('ALTER TABLE quiz_answer_quiz_choice DROP CONSTRAINT FK_99D8FC7F9FE61737');
        $this->addSql('ALTER TABLE quiz_question DROP CONSTRAINT FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_question_choices DROP CONSTRAINT FK_174526FC3101E51F');
        $this->addSql('ALTER TABLE quiz_question_choices DROP CONSTRAINT FK_174526FC9FE61737');
        $this->addSql('ALTER TABLE quiz_question_correct_choices DROP CONSTRAINT FK_92DC58F13101E51F');
        $this->addSql('ALTER TABLE quiz_question_correct_choices DROP CONSTRAINT FK_92DC58F19FE61737');
        $this->addSql('ALTER TABLE quiz_result DROP CONSTRAINT FK_FE2E314A853CD175');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_answer');
        $this->addSql('DROP TABLE quiz_answer_quiz_choice');
        $this->addSql('DROP TABLE quiz_choice');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE quiz_question_choices');
        $this->addSql('DROP TABLE quiz_question_correct_choices');
        $this->addSql('DROP TABLE quiz_result');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
