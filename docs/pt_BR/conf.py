import moodle_docs_theme

project = "moodle-tiny_toolbar"
copyright = "2026 onwards, Kelson da Costa Medeiros"
author = "Kelson da Costa Medeiros"
release = "1.0.0"

extensions = [
    "sphinx.ext.githubpages",
    "moodle_docs_theme",
]

templates_path = []
exclude_patterns = ["_build", "Thumbs.db", ".DS_Store"]

language = "pt_BR"

html_theme = "moodle_docs_theme"
html_theme_path = [moodle_docs_theme.get_html_theme_path()]

html_theme_options = {
    "project_name": "moodle-tiny_toolbar",
    "tagline": "Escolha a barra de ferramentas e o menu do TinyMCE do seu Moodle",
    "github_url": "https://github.com/moodle-by-kelsoncm/tiny_toolbar",
    "github_repo": "moodle-by-kelsoncm/tiny_toolbar",
    "github_version": "main",
    "doc_path": "docs/pt_BR/",
    "show_edit_on_github": True,
    "enable_dark_mode": True,
    "enable_language_selector": True,
    "navigation_links": "Início|index, Instalação|installation, Configuração|configuration, Uso|usage",
}

html_static_path = []
