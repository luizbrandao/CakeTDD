<?php
App::uses('Post','Model');

class PostTest extends CakeTestCase{
	public $fixtures = array('app.post');

	public function setUp(){
		parent::setUp();
		$this->Post = ClassRegistry::init('Post');
	}

	public function testGetAllPosts(){
		$result = $this->Post->GetAllPosts();
		$expected = array(
			array('Post' => array('id' => 1, 'title' => 'The title')),
            		array('Post' => array('id' => 2, 'title' => 'A title once again')),
            		array('Post' => array('id' => 3, 'title' => 'Title strikes back'))
		);

		$this->assertEquals($expected, $result);
	}

	public function testGetSinglePost(){
		$result = $this->Post->getSinglePost(2);
		$expected = array(
			'Post' => array(
				'id'=>2,
				'title'=>'A title once again',
				'body'=>'And the post body follows.',
				'created'=> '2012-07-04 10:41:23',
				'modified'=> '2012-07-04 10:43:31'
			)
		);
		$this->assertEquals($expected, $result);
	}

	public function testAddPost(){
		$postData = array(
			'title'=> 'Test Post Title',
			'body'=>'We love TDD. Yeah!',
			'modified' => '2012-08-08 16:52:56',
		    'created' => '2012-08-08 16:52:56'
		);
		
		$numRecordsBefore = $this->Post->find('count');
		$result = $this->Post->addPost($postData);
		$numRecordsAfter = $this->Post->find('count');

		$expected = array(
			'Post'=>array(
				'id'=>4,
				'title'=>'Test Post Title',
				'body'=> 'We love TDD. Yeah!',
				'modified' => '2012-08-08 16:52:56',
		        	'created' => '2012-08-08 16:52:56'
			)
		);

		$this->assertEquals(4,$numRecordsAfter);
		$this->assertTrue($numRecordsBefore != $numRecordsAfter);
		$this->assertEquals($expected,$result);
	}

	public function testEditPost(){
		$this->Post->id = 3;

		$postData = array(
			'title'=>'The Post Title. Updated',
			'body' =>'We love TDD. Yeah! Yeah!',
			'created'=>'2012-07-04 10:43:23',
			'modified'=>'2012-07-04 10:49:51'
		);

		$numRecordsBeforeEdit = $this->Post->read();
		$numRecordsBefore = $this->Post->find('count');

		$result = $this->Post->editPost($postData);
		$numRecordsAfter = $this->Post->find('count');

		$expected = array(
			'Post' => array(
				'id'=>3,
				'title'=>'The Post Title. Updated',
				'body' =>'We love TDD. Yeah! Yeah!',
				'created'=>'2012-07-04 10:43:23',
				'modified'=>'2012-07-04 10:49:51'
			)
		);

		$this->assertEquals($expected,$result);
		$this->assertTrue($numRecordsBefore == $numRecordsAfter);
	}

	public function testValidation(){
		$postData = array(
			'title'=>'',
			'body'=>'Oh no, this post has and empty title!'
		);

		$result = $this->Post->addPost($postData);
		$invalidFields = $this->Post->invalidFields();

		$this->assertFalse($result);

		$this->assertContains('This field cannot be left blank', $invalidFields['title']);

		$postData = array(
			'title'=>'No body in the post? Impossible.',
			'body'=> ''
		);

		$result = $this->Post->addPost($postData);
		$invalidFields = $this->Post->invalidFields();

		$this->assertFalse($result);
		$this->assertContains('This field cannot be left blank', $invalidFields['body']);

		$postData = array(
			'title'=>'Title...',
			'body'=>' ... and body.'
		);

		$result = $this->Post->addPost($postData);
		$invalidFields = $this->Post->invalidFields();

		$this->assertFalse(empty($result));
		$this->assertTrue(empty($invalidFields));
	}
}
