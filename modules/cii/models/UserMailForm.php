<?php


namespace app\modules\cii\models;

use Yii;

class UserMailForm extends User {
    public $subject;
    public $content;
    public $cc;

    public function attributeLabels() {
        return [
            'subject'   => Yii::t('app', 'Subject'),
            'content'   => Yii::t('app', 'Content'),
            'cc'        => Yii::t('app', 'Send copy to')
        ];
    }

    public function rules() {
        return [
            [['subject', 'content'], 'required'],
            [['subject', 'cc'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['cc'], 'email'],
        ];
    }


    public function send() {
        if($this->validate()) {
            $mailer = Yii::$app->mailer;
            return $mailer->compose()
                ->setSubject($this->subject)
                ->setHtmlBody($mailer->render(
                    $mailer->htmlLayout,
                    ['content' => $this->content]
                ))
                ->setTextBody($mailer->render(
                    $mailer->textLayout,
                    ['content' => strip_tags($this->content)]
                ))
                ->setCc($this->cc)
                ->setTo($this->email)
                ->setFrom(Yii::$app->cii->setting('cii', 'sender'))
                ->send()
            ;
        }
    }
}