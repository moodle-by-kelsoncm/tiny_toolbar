Usage
=====

Toolbar builder
---------------

Go to *Site administration > Plugins > Text editors > TinyMCE editor > Toolbar builder*, or use *Open the toolbar builder* on the Toolbar settings.

.. figure:: img/builder.png
   :alt: The toolbar builder with the menus, the selection toolbar, the groups of the toolbar and the available buttons.
   :width: 100%

   The toolbar builder.

1. *Start from* a preset, or keep the layout in use.
2. Tick the *Menus* to show.
3. Choose the *Selection toolbar*: Moodle default, off, or the buttons you type.
4. Build the *Toolbar*:

   * select a group with its radio button, then select an available button to add it there, or drag the button to any group;
   * move buttons with ‹ and ›, groups with ↑ and ↓, and remove them with ×;
   * *Add group* creates an empty group; rename groups as you like (letters, digits, hyphens and underscores).

5. *Save changes*. The layout becomes the custom layout and the layout in use, and the preview editor at the bottom of the page shows it.

The available buttons come from the editors and plugins installed on the site. A button with a dashed border is in the layout but not available on the site (for example, a disabled plugin); it is not shown in the editor.

What users see
--------------

Every TinyMCE editor of the site uses the layout: course and activity descriptions, forum posts, assignments, pages and so on. Users without ``tiny/toolbar:use`` keep the Moodle default. To go back to the Moodle default for everyone, choose *Moodle default* as the layout in use.
