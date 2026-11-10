# Piloto da Casa da Torre

O piloto usa uma base MySQL 8.4 dedicada, contas locais e dois grupos fictícios.
A instalação abre em [http://127.0.0.1:8088](http://127.0.0.1:8088) e o MySQL fica na rede privada do Docker.

## Instalar

É necessário Docker com Compose v2. A partir da raiz do repositório, copiar
`.env.pilot.example` para `.env.pilot`. Preencher `PILOT_APP_KEY` com
`base64:` seguido de 32 bytes aleatórios em Base64. Preencher as duas
palavras-passe de MySQL com valores aleatórios e distintos. Em Bash, usar
`openssl rand -base64 32` e `openssl rand -hex 24`, respetivamente.

```sh
export SOUTELO_PILOT_EMAIL='pilot@example.test'
read -rs -p 'Palavra-passe do piloto (pelo menos 14 caracteres) ' SOUTELO_PILOT_PASSWORD
export SOUTELO_PILOT_PASSWORD
export SOUTELO_PILOT_TOTP_SECRET="$(python3 -c 'import base64, secrets; print(base64.b32encode(secrets.token_bytes(20)).decode())')"
printf '%s\n' "$SOUTELO_PILOT_TOTP_SECRET"

docker compose --env-file .env.pilot -f compose.pilot.yml build app
docker compose --env-file .env.pilot -f compose.pilot.yml run --rm --user www-data app php artisan migrate --force
docker compose --env-file .env.pilot -f compose.pilot.yml run --rm --user www-data \
    -e SOUTELO_PILOT_EMAIL -e SOUTELO_PILOT_PASSWORD -e SOUTELO_PILOT_TOTP_SECRET \
    app php artisan db:seed --class=SouteloPilotSeeder --force
unset SOUTELO_PILOT_PASSWORD SOUTELO_PILOT_TOTP_SECRET
docker compose --env-file .env.pilot -f compose.pilot.yml up -d app
```

Executar o seeder apenas na instalação inicial. O volume `pilot-mysql` guarda
os dados entre reinícios. O seeder exige a base `soutelo_pilot` vazia, gera o
primeiro utilizador local e associa-lhe o papel de administração existente.

## Experimentar

1. Entrar com o e-mail, a palavra-passe e o código do autenticador.
2. Abrir a grelha de quartos em `/rooms`. T01 tem um lugar e T02 tem dois.
3. Tentar criar uma reserva sobreposta em T01. Verificar que a aplicação
   indica a indisponibilidade. Experimentar uma reserva com início no dia em
   que o grupo anterior sai.
4. Abrir `/report/meal_summary/PILOT-1`. O Grupo de teste 1 chega na sexta-feira
   às 18h e sai no domingo às 11h. Deve aparecer no jantar de sexta-feira e no
   pequeno-almoço de domingo. Os horários de serviço são configuráveis em
   `config/polanco.php`.

## Contas da equipa

Criar uma conta por pessoa. Os perfis disponíveis são receção
(`reception`), cozinha (`kitchen`), limpeza (`housekeeping`) e administração
(`admin`). O comando pede a palavra-passe no terminal. Para administração
apresenta uma chave TOTP que deve ser entregue à própria pessoa por um canal
seguro e adicionada à sua aplicação de autenticação.

```sh
docker compose --env-file .env.pilot -f compose.pilot.yml exec --user www-data app \
    php artisan pilot:user cozinha@example.test --role=kitchen
docker compose --env-file .env.pilot -f compose.pilot.yml exec --user www-data app \
    php artisan pilot:user cozinha@example.test --revoke
```

Voltar a configurar uma conta substitui o perfil e a palavra-passe e
invalida as sessões anteriores. A configuração de contas fica registada
como operação do servidor. Reservar estes comandos ao operador autorizado
e registar a identidade do operador no acesso ao servidor. As exportações
feitas no navegador guardam a identidade da conta que as iniciou.

## Cópias de segurança

O script abaixo gera um ficheiro cifrado para MySQL e outro para os anexos.
Instalar `age` no servidor. Criar o par de chaves num equipamento separado
e configurar no servidor apenas a chave pública. Guardar a chave privada
e uma cópia segura da chave Laravel (`PILOT_APP_KEY`) em locais separados
dos ficheiros de backup. São necessárias para recuperar o serviço e os
segredos cifrados.

```sh
PILOT_BACKUP_RECIPIENT=age1... bash bin/pilot-backup /mnt/copias/casa-da-torre
```

Automatizar a execução diária no servidor da Casa, verificar falhas e
manter uma cópia fora do servidor que a conta da aplicação não possa apagar.
A verificação automática ensaia o restauro de um backup cifrado do MySQL e
a leitura do arquivo de ficheiros. Para um restauro real, usar primeiro
uma instância vazia e confirmar a versão da aplicação, a chave Laravel e
a recuperação dos ficheiros antes de trocar o serviço em uso.

## Acesso na Casa

O Compose só expõe o serviço em `127.0.0.1` por defeito. Instalar um proxy
HTTPS no servidor e restringir a porta do proxy à rede da equipa. Manter
o Wi-Fi de hóspedes separado. Para acesso de fora, usar a VPN da organização
com autenticação de dois fatores e limitar o acesso ao endereço do site.

Definir `PILOT_APP_URL` com o endereço HTTPS e `PILOT_TRUSTED_PROXY` com o
IP exato que o contentor vê para o proxy. Num proxy no próprio anfitrião,
este costuma ser o gateway da rede Docker. Confirmar o IP em vez de confiar
em toda a rede. O proxy deve substituir os cabeçalhos encaminhados e
preservar o nome do site no cabeçalho Host. Validar o certificado nos
computadores da equipa. Com URL HTTPS, o piloto exige HTTPS e ativa cookies
seguros.

Ativar a cifragem do disco do servidor. Usar uma base, volumes, credenciais,
chave Laravel e chave de cópias diferentes para cada casa. Quando duas
instâncias partilham o mesmo anfitrião Docker, usar nomes de projeto Compose
e portas locais diferentes. O nome pode ser definido com
`COMPOSE_PROJECT_NAME` no `.env.pilot`.

Antes de usar dados reais, testar o acesso de um computador da equipa,
de um computador fora da rede, e de uma conta de cada perfil. Confirmar
também o restauro e definir quem aplica as atualizações de segurança.
