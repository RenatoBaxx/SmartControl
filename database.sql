-- Tabela de Professores
CREATE TABLE professor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    apelido VARCHAR(50) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL, -- Para armazenar senhas (deve ser hash)
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Sessões de Login
CREATE TABLE sessao_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    token_sessao VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE
);

-- Tabela de Histórico (se necessário)
CREATE TABLE historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    descricao TEXT NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE
);

-- Tabela de Recursos em Tempo Real (se necessário)
CREATE TABLE tempo_real (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    recurso VARCHAR(100) NOT NULL,
    valor TEXT NOT NULL,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE
);