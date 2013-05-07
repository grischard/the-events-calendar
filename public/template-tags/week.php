<?php
/**
 * Events Calendar Pro Week Template Tags
 *
 * Display functions for use in WordPress templates.
 */

// Don't load directly
if ( !defined('ABSPATH') )
	die('-1');

if( class_exists('TribeEventsPro')) {

	/**
	 * set the loop type for week view between all day and hourly events
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @param  string $loop_type
	 * @return void
	 */
	function tribe_events_week_set_loop_type( $loop_type ){
		Tribe_Events_Pro_Week_Template::$loop_type = $loop_type;
	}

	/**
	 * retrieve the loop type for checking the loop between all day and hourly events
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return string $loop_type
	 */
	function tribe_events_week_get_loop_type(){
		return apply_filters( 'tribe_events_week_get_loop_type', Tribe_Events_Pro_Week_Template::$loop_type );
	}

	/**
	 * setup css classes for daily columns in week view
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return void
	 */
	function tribe_events_week_column_classes(){
		echo apply_filters('tribe_events_week_column_classes', Tribe_Events_Pro_Week_Template::column_classes());
	}

	/**
	 * setup css classes for each single event displayed in week view
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return void
	 */
	function tribe_events_week_event_classes(){
		echo apply_filters('tribe_events_week_event_classes', Tribe_Events_Pro_Week_Template::event_classes());	
	}

	/**
	 * get a list of days of the week with proper offset applied
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return object week days
	 */
	function tribe_events_week_get_days(){
		return apply_filters('tribe_events_week_get_days', Tribe_Events_Pro_Week_Template::get_week_days() );
	}

	/**
	 * get map of all day events for week view
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return array of event ids
	 */
	function tribe_events_week_get_all_day_map(){
		return apply_filters('tribe_events_week_get_all_day_map', Tribe_Events_Pro_Week_Template::get_events('all_day_map') );
	}

	/**
	 * get array of all day events sorted by day
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return array of event objects
	 */
	function tribe_events_week_get_all_day(){
		return apply_filters('tribe_events_week_get_all_day', Tribe_Events_Pro_Week_Template::get_events('all_day') );
	}

	/**
	 * get all day event ids from map specific all day column by current day
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return array of event ids
	 */
	function tribe_events_week_get_all_day_map_col(){
		$all_day_map  = Tribe_Events_Pro_Week_Template::get_events('all_day_map');
		return apply_filters('tribe_events_week_get_all_day_map_col', $all_day_map[ Tribe_Events_Pro_Week_Template::get_current_day() ]);
	}

	/**
	 * get array of hourly event objects
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return array of hourly event objects
	 */
	function tribe_events_week_get_hourly(){
		return apply_filters('tribe_events_week_get_hourly', Tribe_Events_Pro_Week_Template::get_events('hourly') );
	}

	/**
	 * set the current day by day of the week number
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @param  integer $day_id
	 * @return void
	 */
	function tribe_events_week_setup_current_day( $day_id = 0 ){
		Tribe_Events_Pro_Week_Template::set_current_day( $day_id );
	}

	/**
	 * get the current day of the week number
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return int $day_id
	 */
	function tribe_events_week_get_current_day(){
		Tribe_Events_Pro_Week_Template::get_current_day();
	}

	/**
	 * set internal mechanism for setting event id for retrieval with other tags
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @param  int $event_id
	 * @return boolean
	 */
	function tribe_events_week_setup_event( $event_id = null ){
		switch( Tribe_Events_Pro_Week_Template::$loop_type ) {
			case 'allday':
				Tribe_Events_Pro_Week_Template::set_event_id( $event_id );
				return true;
				break;
			case 'hourly':
				$event = Tribe_Events_Pro_Week_Template::get_hourly_event( $event_id );
				if ( !empty($event->EventStartDate) && date( 'Y-m-d', strtotime( $event->EventStartDate ) ) <= Tribe_Events_Pro_Week_Template::get_current_date() && date( 'Y-m-d', strtotime( $event->EventEndDate ) ) >= Tribe_Events_Pro_Week_Template::get_current_date() ) {
					echo Tribe_Events_Pro_Week_Template::get_current_date();
					Tribe_Events_Pro_Week_Template::set_event_id( $event_id );
					return true; 
				} else {
					return false;
				}
				break;
		}
		return false;
	}

	/**
	 * get internal event id pointer
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return int $event_id
	 */
	function tribe_events_week_get_event_id(){
		return apply_filters('tribe_events_week_get_event_id', Tribe_Events_Pro_Week_Template::get_event_id() );
	}

	/**
	 * check to see if event is available or first instance 
	 * used in templating all day event spans and positioning
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return boolean
	 */
	function tribe_events_week_is_not_allday_event_field(){
		$event_key_id = Tribe_Events_Pro_Week_Template::get_event_id();
		if( is_null( $event_key_id ) || in_array( $event_key_id, Tribe_Events_Pro_Week_Template::$event_key_track ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * get event object
	 * @since  3.0
	 * @author tim@imaginesimplicty.com
	 * @return object
	 */
	function tribe_events_week_get_event(){
		switch( Tribe_Events_Pro_Week_Template::$loop_type ) {
			case 'allday':
				$event = Tribe_Events_Pro_Week_Template::get_allday_event();
				$event_id = Tribe_Events_Pro_Week_Template::get_event_id();	
				Tribe_Events_Pro_Week_Template::$event_key_track[] = $event_id;
				break;
			case 'hourly':
				$event = Tribe_Events_Pro_Week_Template::get_hourly_event();
				break;
		}

		return apply_filters( 'tribe_events_week_get_event', $event );
	}

}