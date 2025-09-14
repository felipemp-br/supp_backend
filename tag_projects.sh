#!/bin/sh

# Configurações
TAG_VERSION="${1:-1.24.0}"  # Versão da tag (parâmetro ou padrão)
GITLAB_BASE="https://gitlab.agu.gov.br"
AUTH_FILE="$(dirname "$0")/auth.json"  # Caminho do auth.json (mesmo diretório do script)

# Verifica se o auth.json existe
if [ ! -f "$AUTH_FILE" ]; then
    echo "Erro: Arquivo auth.json não encontrado em $(dirname "$0")" >&2
    exit 1
fi

# Extrai credenciais do auth.json (usando grep/cut para compatibilidade com /bin/sh)
GIT_USERNAME=$(grep '"username"' "$AUTH_FILE" | cut -d'"' -f4)
GIT_PASSWORD=$(grep '"password"' "$AUTH_FILE" | cut -d'"' -f4)

if [ -z "$GIT_USERNAME" ] || [ -z "$GIT_PASSWORD" ]; then
    echo "Erro: Credenciais não encontradas no auth.json" >&2
    exit 1
fi

# Diretório base (sobe dois níveis a partir do diretório atual)
BASE_DIR="$(dirname "$(dirname "$(pwd)")")"  # ~/supp

# Lista de projetos (grupo/repo)
PROJECTS="
supp-core/supp-administrativo-backend
supp-core/supp-administrativo-frontend
supp-core/supp-consultivo-backend
supp-core/supp-consultivo-frontend
supp-core/supp-disciplinar-backend
supp-core/supp-disciplinar-frontend
supp-core/supp-judicial-backend
supp-core/supp-judicial-frontend
supp-core/supp-receita-federal-backend
supp-core/supp-receita-federal-frontend
supp-core/supp-ecarta-backend
supp-core/supp-ecarta-frontend
supp-core/supp-divida-backend
supp-core/supp-divida-frontend
supp-core/supp-calculo-backend
supp-core/supp-calculo-frontend
supp-agu/supp-dossie-social-backend
supp-agu/supp-sigepe-backend
supp-agu/supp-dossie-requisicao-pagamento-backend
supp-agu/supp-dossie-laudo-medico-backend
supp-agu/supp-dossie-previdenciario-backend
supp-agu/supp-dossie-pap-get-backend
supp-agu/supp-labra-backend
supp-agu/supp-gestao-devedor-agu-backend
supp-core/supp-gestao-devedor-backend
supp-agu/supp-honorarios-backend
"

# Verifica se o Git está instalado
if ! command -v git >/dev/null 2>&1; then
    echo "Erro: Git não está instalado." >&2
    exit 1
fi

# Cria os subdiretórios supp-core e supp-agu (se não existirem)
mkdir -p "$BASE_DIR/supp-core" "$BASE_DIR/supp-agu"

# Loop através dos projetos
echo "$PROJECTS" | while IFS= read -r project; do
    [ -z "$project" ] && continue  # Ignora linhas vazias

    group="${project%%/*}"  # supp-core
    repo="${project#*/}"    # supp-administrativo-backend
    project_dir="$BASE_DIR/$project"
    git_url="$GITLAB_BASE/$project.git"

    if [ -d "$project_dir" ]; then
        echo "> Projeto encontrado: $project"
        cd "$project_dir" || continue
    else
        echo "> Clonando: $project"
        cd "$BASE_DIR/$group" || continue

        # Clona com autenticação básica (user:password na URL)
        git clone "https://$GIT_USERNAME:$GIT_PASSWORD@gitlab.agu.gov.br/$project.git"

        cd "$project_dir" || continue
    fi

    # Configura o usuário Git (opcional, mas recomendado para pushes)
    git config --local user.name "$GIT_USERNAME"
    git config --local user.email "$GIT_USERNAME@agu.gov.br"

    # Cria e pusha a tag (com autenticação)
    echo ">> Tagging: $TAG_VERSION"
    git checkout . && \
    git checkout master && \
    git pull && \
    git tag "$TAG_VERSION" && \
    git push "https://$GIT_USERNAME:$GIT_PASSWORD@gitlab.agu.gov.br/$project.git" tag "$TAG_VERSION"

    if [ $? -eq 0 ]; then
        echo "✅ Tag $TAG_VERSION criada/pushada em $project"
    else
        echo "❌ Falha ao taggear $project" >&2
    fi
done

echo "Concluído!"
