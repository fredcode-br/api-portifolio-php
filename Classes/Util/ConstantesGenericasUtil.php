<?php

namespace Util;

abstract class ConstantesGenericasUtil
{
    /* REQUESTS */
    public const TIPO_REQUEST = ['GET', 'POST', 'DELETE', 'PUT'];
    public const TIPO_GET = ['HABILIDADES', 'PROJETOS'];
    public const TIPO_POST = ['HABILIDADES', 'PROJETOS'];
    public const TIPO_DELETE = ['HABILIDADES', 'PROJETOS'];
    public const TIPO_PUT = ['HABILIDADES', 'PROJETOS'];

    /* ERROS */
    public const MSG_ERRO_TIPO_ROTA = 'Rota não permitida!';
    public const MSG_ERRO_RECURSO_INEXISTENTE = 'Recurso inexistente!';
    public const MSG_ERRO_GENERICO = 'Algum erro ocorreu na requisição!';
    public const MSG_ERRO_SEM_RETORNO = 'Nenhum registro encontrado!';
    public const MSG_ERRO_NAO_AFETADO = 'Nenhum registro afetado!';
    public const MSG_ERRO_TOKEN_VAZIO = 'É necessário informar um Token!';
    public const MSG_ERRO_TOKEN_NAO_AUTORIZADO = 'Token não autorizado!';
    public const MSG_ERR0_JSON_VAZIO = 'O Corpo da requisição não pode ser vazio!';

    /* SUCESSO */
    public const MSG_INSERIDO_SUCESSO = 'Registro inserido com Sucesso!';
    public const MSG_DELETADO_SUCESSO = 'Registro deletado com Sucesso!';
    public const MSG_ATUALIZADO_SUCESSO = 'Registro atualizado com Sucesso!';

    /* RECURSO USUARIOS */
    public const MSG_ERRO_ID_OBRIGATORIO = 'ID é obrigatório!';
    public const MSG_ERRO_TAG_OBRIGATORIA = 'Tag é obrigatória!';
    public const MSG_ERRO_TAG_NOME_OBRIGATORIO = 'Tag e nome são obrigatórios!';
    public const MSG_ERRO_NOME_TAGS_OBRIGATORIO = 'Tags e nome são obrigatórios!';
    public const MSG_ERRO_PREENCHA_TODOS_CAMPOS = 'Preencha todos os campos!';
    public const MSG_ERRO_TAG_ARRAY = 'O campo tag deve ser preenchio com um array de strings!';
    public const MSG_ERRO_NOME_OBRIGATORIO = 'O nome é obrigatório!';

    /* RETORNO JSON */
    const TIPO_SUCESSO = 'sucesso';
    const TIPO_ERRO = 'erro';

    /* OUTRAS */
    public const SIM = 'S';
    public const TIPO = 'tipo';
    public const RESPOSTA = 'resposta';
}