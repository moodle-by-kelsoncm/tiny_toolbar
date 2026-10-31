Installation
============

Requirements
------------

* Moodle 4.5 LTS or 5.3 LTS, with the TinyMCE editor.
* PHP supported by your Moodle version.

From a ZIP file
---------------

1. Download ``tiny_toolbar-<version>.zip`` from the `releases <https://github.com/moodle-by-kelsoncm/tiny_toolbar/releases>`_.
2. Go to *Site administration > Plugins > Install plugins*, upload the ZIP and follow the steps.
3. Finish the upgrade at *Site administration > Notifications*.

From source
-----------

Copy the repository to ``lib/editor/tiny/plugins/toolbar`` (``public/lib/editor/tiny/plugins/toolbar`` on Moodle 5.1 or later) and visit *Site administration > Notifications*:

.. code-block:: bash

   git clone https://github.com/moodle-by-kelsoncm/tiny_toolbar.git public/lib/editor/tiny/plugins/toolbar

After installing, the editor keeps the Moodle default until you choose another layout (see :doc:`configuration`).

Coming from local_tinytoolbar
-----------------------------

The old ``local_tinytoolbar`` package installed the component ``tool_tinycustomizer``, which never changed the editor. It is a different plugin: uninstall it at *Site administration > Plugins > Plugins overview*. Its settings are not migrated.

Languages
---------

The package ships only the English strings. Other languages, including Brazilian Portuguese, come from the Moodle translation tool (AMOS), through the language packs.
