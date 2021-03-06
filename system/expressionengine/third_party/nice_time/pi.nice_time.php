<?php
	if (!defined('BASEPATH'))
		exit('No direct script access allowed');

	$plugin_info = array(
		'pi_name'			 => 'Nice Time',
		'pi_version'		 => '0.4',
		'pi_author'			 => 'Arthur Vincent Simon',
		'pi_author_email'	 => 'arthurvincent.simon@gmail.com',
		'pi_author_url'		 => 'http://twitter.com/arvinsim/',
		'pi_description'	 => 'A fork of pvledoux Nice Time plugin with multilanguage features',
		'pi_usage'			 => Nice_time::usage()
	);

	/**
	 * Copyright (c) 2013, Pv Ledoux
	 * Copyright (c) 2013, Arthur Vincent Simon
	 * All rights reserved.
	 *
	 * Redistribution and use in source and binary forms, with or without
	 * modification, are permitted provided that the following conditions are met:
	 * 	* Redistributions of source code must retain the above copyright
	 * 	   notice, this list of conditions and the following disclaimer.
	 * 	* Redistributions in binary form must reproduce the above copyright
	 * 	   notice, this list of conditions and the following disclaimer in the
	 * 	   documentation and/or other materials provided with the distribution.
	 * 	* Neither the name of the <organization> nor the
	 * 	   names of its contributors may be used to endorse or promote products
	 * 	   derived from this software without specific prior written permission.
	 *
	 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	 * DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
	 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
	 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	 */

	/**
	 * Nice_time
	 *
	 * @copyright	Arthur Vincent Simon 2013
	 * @since		02 Sept 2013
	 * @author		Arthur Vincent Simon <arthurvincent.simon@gmail.com>
	 * @link		https://github.com/arvinsim
	 *
	 */
	class Nice_time
	{
		/**
		 * Constructor.
		 *
		 * @access	public
		 * @return	void
		 */
		public function __construct()
		{
			ee()->lang->loadfile('nice_time');
		}

		private function _is_timestamp($string)
		{
			return ( 1 === preg_match('~^[1-9][0-9]*$~', $string) );
		}

		private function _is_plural($num)
		{
			if ($num != 1)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}

		private function _get_multilanguage_time_ago_string($time_type, $diff, $is_plural, $frame)
		{
			switch ($time_type)
			{
				case 'second':
					if ($is_plural === FALSE)
					{
						return sprintf(lang('time_ago_second'), $diff, lang($frame));
					}
					else
					{
						return sprintf(lang('time_ago_seconds'), $diff, lang($frame));
					}
					break;
				case 'minute':
					if ($is_plural === FALSE)
					{
						return sprintf(lang('time_ago_minute'), $diff, lang($frame));
					}
					else
					{
						return sprintf(lang('time_ago_minutes'), $diff, lang($frame));
					}
					break;
				case 'hour':
					if ($is_plural === FALSE)
					{
						return sprintf(lang('time_ago_hour'), $diff, lang($frame));
					}
					else
					{
						return sprintf(lang('time_ago_hours'), $diff, lang($frame));
					}
					break;
				case 'day':
					if ($is_plural === FALSE)
					{
						return sprintf(lang('time_ago_day'), $diff, lang($frame));
					}
					else
					{
						return sprintf(lang('time_ago_days'), $diff, lang($frame));
					}
					break;
				case 'week':
					if ($is_plural === FALSE)
					{
						return sprintf(lang('time_ago_week'), $diff, lang($frame));
					}
					else
					{
						return sprintf(lang('time_ago_weeks'), $diff, lang($frame));
					}
					break;
				default:
					return '';
			}
		}

		private function _get_converted_time_string($date)
		{
			if (!$this->_is_timestamp($date))
			{
				$date = ee()->localize->string_to_timestamp($date);
			}

			$diff = ee()->localize->now - $date;


			if ($diff >= 0 && $diff < 5)
			{
				return lang('time_ago_now');
			}

			$frame = ($diff < 0) ? 'frame_from now' : 'frame_ago';
			$diff *= ($diff < 0) ? -1 : 1;

			if ($diff < 60)
			{
				return $this->_get_multilanguage_time_ago_string('second', $diff, $this->_is_plural($diff), $frame);
			}

			$diff = round($diff / 60);

			if ($diff < 60)
			{
				return $this->_get_multilanguage_time_ago_string('minute', $diff, $this->_is_plural($diff), $frame);
			}

			$diff = round($diff / 60);

			if ($diff < 24)
			{
				return $this->_get_multilanguage_time_ago_string('hour', $diff, $this->_is_plural($diff), $frame);
			}

			$diff = round($diff / 24);

			if ($diff < 7)
			{
				return $this->_get_multilanguage_time_ago_string('day', $diff, $this->_is_plural($diff), $frame);
			}

			$diff = round($diff / 7);

			if ($diff < 4)
			{
				return $this->_get_multilanguage_time_ago_string('week', $diff, $this->_is_plural($diff), $frame);
			}

			// Default
			return ee()->localize->format_date(lang('format_full_date'), $date);
		}

		public function convert()
		{
			$date = ee()->TMPL->fetch_param('date', time());

			return $this->_get_converted_time_string($date);
		}

		public function convert_multiple()
		{
			// Get datetimes from param
			// If not present, try to get it in GET/POST
			$datetimes = ee()->TMPL->fetch_param('datetimes', ee()->input->get_post('datetimes'));

			$variables		 = array();
			$variable_row	 = array(
				'converted_date_list' => array()
			);

			// Fetch contents of the tag pair, ie, the form contents
			$tagdata = ee()->TMPL->tagdata;

			if (empty($datetimes) === FALSE)
			{
				$dates_list = explode('|', $datetimes);

				$count			 = 1;
				$total_results	 = count($dates_list);
				foreach ($dates_list as $date_item)
				{
					$data = array(
						'datetime'		 => $date_item,
						'time_ago'		 => $this->_get_converted_time_string($date_item),
						'count'			 => $count,
						'total_results'	 => $total_results
					);

					$variable_row['converted_date_list'][] = $data;

					$count++;
				}
				$variables[] = $variable_row;

				// Parse Language variables
				$tagdata = ee()->TMPL->parse_variables($tagdata, $variables);
			}

			return $tagdata;
		}

		/**
		 * Usage
		 *
		 * @return string
		 */
		public function usage()
		{
			ob_start();
			?>

			#Nice_time

			A fork of [pvledoux Nice Time plugin](https://github.com/pvledoux/Nice_time.ee2_addon) with multilanguage features

			## Differences from the original project
			* Multilanguage Support
			* Removed format, relative and prefix parameters. This is because all of these are now defined in the language files. 
			* Two ways to use the plugin as opposed to just one

			There are two ways to use this plugin

			## {exp:nice_time:convert}

			Use this if you only plan to convert only one date

			### Syntax
			```
			{exp:nice_time:convert date="{entry_date}"}
			{exp:nice_time:convert date="2012-09-{segment_3}"}
			{exp:nice_time:convert date="+3 days"}
			```

			### Parameter
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>date</td>
						<td>Can be a string date or a unix timestamp</td>
					</tr>
				</tbody>
			</table>

			## {exp:nice_time:convert_multiple}

			Use this if you plan to convert more than one date.
			This is not part of the core functionality. Rather, I created this to fit some of my needs(API related stuff)
			I will be updating this in future so that it can accept params.

			### Syntax
			```
			{exp:nice_time:convert_multiple}
				{converted_date_list}
					{datetime}
					{time_ago}
				{/converted_date_list}
			{/exp:nice_time:convert_multiple}
			```

			### Parameter
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>date</td>
						<td>The actual list of datetimes. It can be passed via paramater "datetimes="" or via GET/POST. The format looks like "datetime1\|datetime2\|datetime3\|...". For example, "2013-09-04 6:31 pm|2012-10-16 6:31 am"</td>
					</tr>
				</tbody>
			</table>

			## Defining languages

			To add a new language, just add a language file inside Nice Time's languages folder 
			with this format 

			```
			<language>/nice_time_lang.php
			```

			The new language must be one of the languages defined in system/expressionengine/language/.

			This plugin decides what language to show based on the user's preferred language as set by ExpressionEngine

			Take a look at languages/english/nice_time_lang.php to know what language variables to define.

			## TODOs
			* Implement multilanguage support when outputting actual date as opposed to "time ago" phrases

			<?php
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		}
		// --------------------------------------------------------------------
	}

	/* End of file pi.nice_time.php */
	/* Location: ./system/expressionengine/third_party/nice_time/pi.nice_time.php */