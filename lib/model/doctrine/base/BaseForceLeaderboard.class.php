<?php

/**
 * BaseForceLeaderboard
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $tournament_id
 * @property integer $timestamp
 * @property integer $rank
 * @property integer $user_id
 * @property text $user_name
 * @property integer $power
 * @property ForceTournament $rel_tournament
 * 
 * @method integer          getTournamentId()   Returns the current record's "tournament_id" value
 * @method integer          getTimestamp()      Returns the current record's "timestamp" value
 * @method integer          getRank()           Returns the current record's "rank" value
 * @method integer          getUserId()         Returns the current record's "user_id" value
 * @method text             getUserName()       Returns the current record's "user_name" value
 * @method integer          getPower()          Returns the current record's "power" value
 * @method ForceTournament  getRelTournament()  Returns the current record's "rel_tournament" value
 * @method ForceLeaderboard setTournamentId()   Sets the current record's "tournament_id" value
 * @method ForceLeaderboard setTimestamp()      Sets the current record's "timestamp" value
 * @method ForceLeaderboard setRank()           Sets the current record's "rank" value
 * @method ForceLeaderboard setUserId()         Sets the current record's "user_id" value
 * @method ForceLeaderboard setUserName()       Sets the current record's "user_name" value
 * @method ForceLeaderboard setPower()          Sets the current record's "power" value
 * @method ForceLeaderboard setRelTournament()  Sets the current record's "rel_tournament" value
 * 
 * @package    edgeworld
 * @subpackage model
 * @author     BRIGADA
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseForceLeaderboard extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('force_leaderboard');
        $this->hasColumn('tournament_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('timestamp', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('rank', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_name', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('power', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('ForceTournament as rel_tournament', array(
             'local' => 'tournament_id',
             'foreign' => 'id'));
    }
}