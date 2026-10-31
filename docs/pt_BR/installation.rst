Instalação
==========

Requisitos
----------

* Moodle 4.5 LTS ou 5.3 LTS, com o editor TinyMCE.
* PHP suportado pela sua versão do Moodle.

A partir de um arquivo ZIP
--------------------------

1. Baixe ``tiny_toolbar-<versão>.zip`` nas `releases <https://github.com/moodle-by-kelsoncm/tiny_toolbar/releases>`_.
2. Acesse *Administração do site > Plugins > Instalar plugins*, envie o ZIP e siga os passos.
3. Conclua a atualização em *Administração do site > Notificações*.

A partir do código-fonte
------------------------

Copie o repositório para ``lib/editor/tiny/plugins/toolbar`` (``public/lib/editor/tiny/plugins/toolbar`` no Moodle 5.1 ou posterior) e acesse *Administração do site > Notificações*:

.. code-block:: bash

   git clone https://github.com/moodle-by-kelsoncm/tiny_toolbar.git public/lib/editor/tiny/plugins/toolbar

Depois de instalado, o editor continua com o padrão do Moodle até você escolher outra barra (veja :doc:`configuration`).

Vindo do local_tinytoolbar
--------------------------

O antigo pacote ``local_tinytoolbar`` instalava o componente ``tool_tinycustomizer``, que nunca mudava o editor. É outro plugin: desinstale-o em *Administração do site > Plugins > Visão geral dos plugins*. As configurações dele não são migradas.

Idiomas
-------

O pacote traz apenas as strings em inglês. Os demais idiomas, inclusive o português do Brasil, vêm da ferramenta de tradução do Moodle (AMOS), pelos pacotes de idioma.
