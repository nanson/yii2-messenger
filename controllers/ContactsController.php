<?php

namespace nanson\messenger\controllers;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * Class MessagesController
 * @package nanson\messenger\controllers
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class ContactsController extends Controller
{
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			]
		]);
	}

	/**
	 * Render contacts list
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Render messages by Contact
	 * @return string
	 */
	public function actionMessages($id)
	{
		return $this->render('view', [
			'contactId' => $id,
		]);
	}

}