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

##Licence
Copyright (c) 2012, Pv Ledoux All rights reserved.

Copyright (c) 2013, Arthur Vincent Simon All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the <organization> nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.