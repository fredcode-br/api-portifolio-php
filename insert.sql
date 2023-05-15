INSERT INTO tokens_autorizados (token, status) VALUES('6460da44cc394', 'S');

INSERT INTO habilidades (tag, nome, porcentagem, cor, icone) VALUES ('html', 'HTML', '70', '#AAAAAA', 'html.svg');
INSERT INTO habilidades (tag, nome, porcentagem, cor, icone) VALUES ('css', 'CSS', '60', '#BBBBBB', 'css.svg');
INSERT INTO habilidades (tag, nome, porcentagem, cor, icone) VALUES ('php', 'PHP', '70', '#CCCCCC', 'php.svg');


INSERT INTO projetos (nome, imagemUrl, projetoUrl, githubUrl, descricao, visualizacoes) 
VALUES ('Projeto 1', 'www.imagem.teste', 'www.projeto.teste', 'www.github.teste', 'Descrição do projeto 1', '0');
INSERT INTO projetos (nome, imagemUrl, projetoUrl, githubUrl, descricao, visualizacoes) 
VALUES ('Projeto 2', 'www.imagem.teste', 'www.projeto.teste', 'www.github.teste', 'Descrição do projeto 2', '4');
INSERT INTO projetos (nome, imagemUrl, projetoUrl, githubUrl, descricao, visualizacoes) 
VALUES ('Projeto 3', 'www.imagem.teste', 'www.projeto.teste', 'www.github.teste', 'Descrição do projeto 3', '3');
INSERT INTO projetos (nome, imagemUrl, projetoUrl, githubUrl, descricao, visualizacoes) 
VALUES ('Projeto 4', 'www.imagem.teste', 'www.projeto.teste', 'www.github.teste', 'Descrição do projeto 4', '1');

INSERT INTO projetos_has_habilidades (projetos_id, habilidades_tag) VALUES (1, 'html');
INSERT INTO projetos_has_habilidades (projetos_id, habilidades_tag) VALUES (1, 'css');
INSERT INTO projetos_has_habilidades (projetos_id, habilidades_tag) VALUES (1, 'php');
INSERT INTO projetos_has_habilidades (projetos_id, habilidades_tag) VALUES (3, 'php');
INSERT INTO projetos_has_habilidades (projetos_id, habilidades_tag) VALUES (3, 'html');
 
SELECT * FROM tokens_autorizados;
SELECT * FROM habilidades;
SELECT * FROM projetos;
SELECT * FROM projetos_has_habilidades;

SELECT P.id, P.nome, P.imagemUrl, 
P.projetoUrl, P.githubUrl, P.descricao, P.visualizacoes, 
GROUP_CONCAT(DISTINCT PH.habilidades_tag ) AS tags
FROM projetos AS P
INNER JOIN projetos_has_habilidades AS PH  ON P.id = PH.projetos_id
INNER JOIN projetos_has_habilidades ON PH.projetos_id = PH.projetos_id 
GROUP BY  P.id;