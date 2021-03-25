Toronto Ward Lookup
===================

Assigns ward numbers to Toronto street addresses. Works with Drupal and
CiviCRM.

Creates a Drupal block for searching for a ward number that matches the typed 
in address. If it matches then it redirects the user to the URL that matches
ward/ward#. If an address matches multiple wards then it offers a choice.

With CiviCRM contacts it only returns one likely ward number. It will lookup
addresses on cron and when a contribution form is submitted. There is also
manual batch process button.

NOTE: the CiviCRM custom fields for Ward Number are hardcoded! You'll need
to change "custom_28" to whatever custom field you create for your CiviCRM contacts.
