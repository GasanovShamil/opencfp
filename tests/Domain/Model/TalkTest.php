<?php

namespace OpenCFP\Test\Domain\Model;

use OpenCFP\Domain\Model\Talk;
use OpenCFP\Domain\Model\TalkMeta;
use OpenCFP\Test\DatabaseTestCase;

/**
 * @group db
 */
class TalkTest extends DatabaseTestCase
{

    /**
     * Illuminate doesn't like the fact that we use the PDO begin transaction and rollback
     * so we need to use the ones from the Illuminate capsule to do that instead.
     */
    public function setUp()
    {
        $this->migrate();
        $this->getCapsule()->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->capsule->getConnection()->rollBack();
    }


    /** @test */
    public function getRecentReturnsAnArrayOfTalks()
    {
        $this->generateTalks();
        $talk = new Talk;
        $recent = $talk->getRecent(1);
        $this->assertEquals(4, count($recent));
        $anotherRecent = $talk->getRecent(1, 3);
        $this->assertEquals(3, count($anotherRecent));
    }

    /**
     * @test
     */
    public function createFormattedOutputWorksWithNoMeta()
    {
        $this->generateOneTalk();
        $talk = new Talk;
        $format =$talk->createdFormattedOutput($talk->first(), 1);

        $this->assertEquals('One talk to rule them all', $format['title']);
        $this->assertEquals('api', $format['category']);
        $this->assertEquals(['rating' => 0, 'viewed' => 0], $format['meta']);
        $this->assertTrue(!isset($format['user']));
    }

    /**
     * @test
     */
    public function createFormattedOutputWorksWithMeta()
    {
        $this->generateOneTalk();
        $talk = new Talk;

        // Now to see if the meta gets put in correctly
        $secondFormat =$talk->createdFormattedOutput($talk->first(), 2);

        $this->assertEquals(1, $secondFormat['meta']->rating);
        $this->assertEquals(1, $secondFormat['meta']->viewed);
    }


    private function generateOneTalk()
    {
        $talk = new Talk();

        $talk->create(
            [
                'user_id' => 1,
                'title' => 'One talk to rule them all',
                'description' => 'Two is fine too',
                'type' => 'regular',
                'level' => 'entry',
                'category' => 'api',
            ]
        );

        $meta = new TalkMeta();
        $meta->create(
            [
                'admin_user_id' => 2,
                'rating' => 1,
                'viewed' => 1,
                'talk_id' => $talk->first()->id,
                'created' => new \DateTime(),
            ]
        );
    }


    /**
     * Helper function that generates some talks for us
     */
    private function generateTalks()
    {
        $talk = new Talk();

        $talk->create(
            [
                'user_id' => 1,
                'title' => 'One talk to rule them all',
                'description' => 'Two is fine too',
                'type' => 'regular',
                'level' => 'entry',
                'category' => 'api',
            ]
        );

        $talk->create(
            [
                'user_id' => 1,
                'title' => 'My second talk',
                'description' => 'I told you two is fine',
                'type' => 'regular',
                'level' => 'entry',
                'category' => 'api',
            ]
        );

        $talk->create(
            [
                'user_id' => 1,
                'title' => 'Third times a charm',
                'description' => 'But you cant be too sure ',
                'type' => 'regular',
                'level' => 'entry',
                'category' => 'api',
            ]
        );

        $talk->create(
            [
                'user_id' => 1,
                'title' => 'Lets do one more',
                'description' => 'You know, just in case',
                'type' => 'regular',
                'level' => 'entry',
                'category' => 'api',
            ]
        );
    }
}