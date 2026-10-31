moodle-tiny_toolbar
===================

**Choose the toolbar and the menus of every TinyMCE editor on your Moodle site, with presets or a visual builder.**

.. figure:: img/editor.png
   :alt: A TinyMCE editor with a short custom toolbar and a selection toolbar with bold, italic, headings and quote.
   :width: 100%

   An editor with a custom toolbar and selection toolbar.

* **The toolbar control Atto had.** Moodle's TinyMCE only lets administrators enable or disable whole plugins. This plugin chooses which buttons appear, in which order and groups, and which menus.
* **The final word.** The layout is applied after every other editor plugin has added its buttons, so it is exactly what users see.
* **Presets and a builder.** Start from a preset (minimal, simple, standard, accessibility) or build the layout with the buttons the site really has.
* **Per role.** Users without the capability ``tiny/toolbar:use`` keep the Moodle default.

Versão em português: `Português (Brasil) <../pt-br/index.html>`_.

.. toctree::
   :maxdepth: 2
   :caption: Contents

   installation
   configuration
   usage

Main features
-------------

* Layout in use for the whole site: Moodle default (no change), minimal, simple, standard, accessibility or custom.
* Custom layout as JSON, checked on save: toolbar groups, menus and selection toolbar.
* Toolbar builder with the buttons registered on the site, groups, ordering, drag and drop, menus, selection toolbar and a preview editor.
* No database tables and no personal data.
* **Moodle 4.5 LTS and 5.3 LTS**: tested on a CI matrix with PostgreSQL and MariaDB.

Links
-----

* Source code and issues: https://github.com/moodle-by-kelsoncm/tiny_toolbar
