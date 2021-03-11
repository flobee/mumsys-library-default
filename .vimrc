"
" extended setup of default vimrc
"
" For your default vimrc to allow custom vimrc's on top of the default vimrc
"set exrc
"
"
"

syntax enable

set tags=./.php.ctags

" {{{ ## syntastic 4 mumsys project
let g:syntastic_php_phpcs_args = "--standard=misc/coding/Mumsys2"
" }}}

" {{{ ## NERDTree 4 mumsys project
" enable by default
autocmd vimenter * NERDTree
" }}}

