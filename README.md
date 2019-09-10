# Conditional Role Assignment #

Conditional Role Assignment  is a Moodle local plugin to provide automatic rule based assignment of roles to context (e.g. coursecategory) by flexible criteria e.g. userprofile field patterns.

## Application example 1
You want to assign all users with userprofilefield "department" holding the value "elearning" the Moodle role "manager" at course category level "compliance trainings" 

Conditional Role Assignment solves this feature request by providing rule based assignment of the rolees:
1. Assign Role "Manager" to all users matching the condition "userprofilefiled department = elearning" in context "course category = compliance training"

## Application example 2
The scanario is a Moodle instance with two "types" of users which are identified by the userprofilefield "institution" holding values of
"Austria" and "Germany" respectively. There are two Moodle course categories "Courses Austria" and "Courses Germany".
User with institution=Austria shall only see course category "Courses Austria" an NOT "Courses Germany" and vice versa.
This can be achieved by using the Moodle capability category:viewcourselist, removing it from the standard roles and adding it to a newly
defined role "Courselistviewer". In standard Moodle the roles at course category levele would have to be assigned manually.

Conditional Role Assignment solves this feature request by providing rule based assignment of the rolees:
1. Assign Role "Courselistviewer" to all users matching the condition "userprofilefiled institution = Austria" in context "course category = Courses Austria"
2. Assign Role "Courselistviewer" to all users matching the condition "userprofilefiled institution = Germany" in context "course category = Courses Germany"

## Current functionality
1. All rolles defined in your moodle instance can be used
2. The context "course category" is currently the only contexttype available
3. The matching of standard Moodle user profile field entries are currently the only conditions available

## Requirements
The plugin is designed for Moodle 3.5+

## Installation
+ Copy the module code directly to the local/edaktik_condrole directory.
+ Log into Moodle as administrator.
+ Open the administration area (http://your-moodle-site/admin) to start the installation automatically.
+ Select the Moodle context levels that shall be available when creating rules
+ Select the Moodle roles that shall be available when creating rules

## Configuration
+ Log into Moodle as administrator
+ Navigate to Site Administration > Users > Permissions > Conditional Role Assigmnment
+ Add rules

## Adding rules
+ Add a title for your rule: Compliance Training Mangers
+ Add a description for your rule: This rule adds users with userprofilefield "department" holding the value "elearning" the Moodle role "manager" at course category level "compliance trainings" 
+ Set rule to "active" [x] if you want the assignment of roles to be performed when saving the rule form
+ Select the contexttype "Course category"
+ Select the role to be assigned "Manager"
+ Add the condition: profilefield, department, =, elearning
+ Save & Done

## License ##

Copyright   (C) 2019 eDaktik.at
 
Authors:    Andreas Hruska <andreas.hruska@edaktik.at>; Philipp Hager <philipp.hager@edaktik.at>; Thomas Schallert <thomas.schallert@fhnw.ch>; Ivan Gula <ivan.gula.wien@gmail.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.