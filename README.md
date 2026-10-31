# moodle-tiny_toolbar

[English](#english) · [Português (Brasil)](#português-brasil)

Documentation / Documentação: <https://moodle-by-kelsoncm.github.io/tiny_toolbar/>

## English

TinyMCE plugin that lets administrators choose the toolbar and the menus of every TinyMCE editor on a Moodle site, as the Atto toolbar setting used to allow. Pick a preset (minimal, simple, standard, accessibility) or build your own layout in a visual builder that lists the buttons the site really has. The layout is applied after every other plugin has added its buttons, so it is the final word. Supports Moodle 4.5 LTS and 5.3 LTS.

Install: copy this repository to `lib/editor/tiny/plugins/toolbar` (`public/lib/editor/tiny/plugins/toolbar` on Moodle 5.1+) and visit *Site administration > Notifications*. Then go to *Site administration > Plugins > Text editors > TinyMCE editor > Toolbar*.

## Português (Brasil)

Plugin do TinyMCE que deixa o administrador escolher a barra de ferramentas e os menus de todos os editores TinyMCE do Moodle, como a configuração de barra do Atto permitia. Escolha um modelo (mínimo, simples, padrão, acessibilidade) ou monte o seu num construtor visual que lista os botões que o site tem de fato. A barra é aplicada depois que todos os outros plugins colocaram os seus botões, então é a palavra final. Suporta Moodle 4.5 LTS e 5.3 LTS.

Instalação: copie este repositório para `lib/editor/tiny/plugins/toolbar` (`public/lib/editor/tiny/plugins/toolbar` no Moodle 5.1+) e acesse *Administração do site > Notificações*. Depois vá em *Administração do site > Plugins > Editores de texto > Editor TinyMCE > Toolbar*.

## Development / Desenvolvimento

- JavaScript sources are in `amd/src`; rebuild `amd/build` with `npx grunt amd --root=public/lib/editor/tiny/plugins/toolbar` in a Moodle checkout.
- CI (`.github/workflows/ci.yml`) runs `moodle-plugin-ci` on Moodle 4.5 and 5.3 with PostgreSQL and MariaDB; run it locally with [`act`](https://github.com/nektos/act) before pushing.

## Support and privacy / Suporte e privacidade

- Issue tracker / Rastreador de issues: <https://github.com/moodle-by-kelsoncm/tiny_toolbar/issues>
- Privacy / Privacidade: the plugin stores only site settings; no personal data and nothing leaves Moodle / o plugin guarda só configurações do site; nenhum dado pessoal e nada sai do Moodle.
- Translations / Traduções: only English is shipped; other languages go through AMOS / o pacote traz só inglês; outros idiomas via AMOS.

## History / Histórico

This repository was `local_tinytoolbar` (component `tool_tinycustomizer`), whose admin page never reached the editor. Version 1.0.0 rebuilds it as a TinyMCE subplugin. / Este repositório era o `local_tinytoolbar` (componente `tool_tinycustomizer`), cuja página de administração nunca chegava ao editor. A versão 1.0.0 o reconstrói como subplugin do TinyMCE.

## License / Licença

GNU GPL v3 or later. See [LICENSE](LICENSE).
