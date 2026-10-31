Configuração
============

Acesse *Administração do site > Plugins > Editores de texto > Editor TinyMCE > Toolbar* (ou busque "toolbar" na administração).

.. figure:: img/settings.png
   :alt: Configurações do Toolbar com a barra em uso e a barra personalizada em JSON.
   :width: 100%

   As configurações do Toolbar.

Barra em uso
------------

Padrão do Moodle
   O editor fica como os plugins instalados o arrumam. É o valor depois da instalação.

Mínima
   Desfazer, refazer, negrito, itálico, listas e link. Sem menus.

Simples
   Acrescenta sublinhado, imagem, vídeo e equação. Sem menus. Boa para fóruns e tarefas.

Padrão
   Estilos de parágrafo, formatação, alinhamento, listas, recuo, link, mídia, H5P, equação, tabela e tela cheia, com os menus.

Acessibilidade
   Uma barra curta com estilos de parágrafo e o verificador de acessibilidade, e os menus que ajudam a escrever conteúdo acessível.

Personalizada
   A barra personalizada abaixo, normalmente escrita pelo construtor.

Barra personalizada
-------------------

Um objeto JSON:

.. code-block:: json

   {
       "toolbar": [
           {"name": "history", "items": ["undo", "redo"]},
           {"name": "formatting", "items": ["bold", "italic", "underline"]},
           {"name": "content", "items": ["tiny_link_link", "tiny_media_image"]}
       ],
       "menubar": "edit insert format",
       "quickbars": "bold italic | h3 h4 blockquote"
   }

``toolbar``
   Lista de grupos (até 30). Cada grupo tem um ``name`` e os seus ``items``, os nomes dos botões (até 50). Os botões dos plugins do Moodle têm nomes como ``tiny_link_link`` ou ``tiny_media_image``; o construtor mostra todos.

``menubar``
   ``false`` para nenhum menu, ou uma lista com ``file``, ``edit``, ``view``, ``insert``, ``format``, ``tools``, ``table`` e ``help``.

``quickbars``
   Opcional. A barra que aparece sobre o texto selecionado: nomes de botões separados por espaço, com ``|`` entre grupos, ou ``false`` para desligá-la. Sem ela, o editor mantém a barra de seleção do Moodle. O editor nunca a mostra em áreas de texto pequenas, e alguns botões do Moodle, como o de link, não aparecem nela.

A barra é conferida ao salvar e guardada normalizada. Um botão que não existe no site simplesmente não aparece.

Permissão
---------

``tiny/toolbar:use`` (concedida a todos os papéis por padrão)
   Quem não a tem fica com a barra padrão do Moodle. Proíba-a para um papel para deixá-lo fora da barra escolhida.
