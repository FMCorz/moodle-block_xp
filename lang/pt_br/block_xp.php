<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['addrulesformhelp'] = 'A última coluna define a quantidade de pontos de experiência ganhos quando um critério é satisfeito.';
$string['basexp'] = 'Algorítmo base';
$string['changelevelformhelp'] = 'Se você alterar o número de níveis, os emblemas customizados de níveis serão temporariamente desabilitados para prevenir níveis sem emblemas. Se você alterar o número de níveis vá para a página \'Visuais\' para re-habilitar os emblemas customizados uma vez que tenha salvo esse formulário.';
$string['cachedef_filters'] = 'Filtro de nível';
$string['coefxp'] = 'Coeficiente do algorítmo';
$string['comparisonrule'] = 'Regra de comparação';
$string['configdescription'] = 'Descrição para anexar';
$string['configheader'] = 'Configuração';
$string['configtitle'] = 'Título';
$string['congratulationsyouleveledup'] = 'Parabéns!';
$string['coolthanks'] = 'Legal, obrigado!';
$string['courselog'] = 'Log de curso';
$string['coursereport'] = 'Relatório do curso';
$string['courserules'] = 'Regras do curso';
$string['coursesettings'] = 'Configurações do curso';
$string['coursevisuals'] = 'Visuais do curso';
$string['createnewrulefromthisevent'] = 'Criar uma nova regra para esse evento';
$string['customizelevels'] = 'Customizar os níveis';
$string['defaultrulesformhelp'] = 'Essas são as regras padrão fornecidas pelo plugin, elas dão pontos de experiência automaticamente e ignoram alguns eventos redundantes. Regras personalizadas têm precedência sobre elas.';
$string['description'] = 'Descrição';
$string['enableinfos'] = 'Habilitar página de informações';
$string['enableinfos_help'] = ' Quando setado para \'Não\', estudantes não serão capazes de ver a página de informações.';
$string['enableladder'] = 'Habilitar o ranking';
$string['enableladder_help'] = 'Quando setado para \'Não\', estudantes não serão capazes de ver o ranking.';
$string['enablelevelupnotif'] = 'Habilitar notifições de ganho de nível';
$string['enablelevelupnotif_help'] = ' Quando setado para \'Sim\', estudantes verão um popup de felicitação pelo novo nível alcançado.';
$string['enablelogging'] = 'Habilitar logging';
$string['enablexpgain'] = 'Habilitar ganho de XP';
$string['enablexpgain_help'] = 'Quando setado para \'Não\', ninguém ganhará pontos de experiência no curso. Isso é útil para congelar a experiência ganha, ou para habilitar isso por um certo período de tempo.

Por favor, note que isto pode ser controlado de forma mais granulada usando a capacidade \'block/xp:earnxp\'.';
$string['errorformvalues'] = 'Existem alguns erros nos valores do formulário, por favor, resolva-os.';
$string['errorlevelsincorrect'] = 'O número mínimo de níveis é 2';
$string['errornotalllevelsbadgesprovided'] = 'Nem todos os emblemas de níveis foram informados. Faltando: {$a}';
$string['errorxprequiredlowerthanpreviouslevel'] = 'A experiência necessária é menor ou igual ao nível anterior.';
$string['event_user_leveledup'] = 'Usuário passou de nível';
$string['eventname'] = 'Nome do evento';
$string['eventproperty'] = 'Prioridade do evento';
$string['eventtime'] = 'Data do evento';
$string['existingruleformhelp'] = 'Use o primeiro campo para re-ordenar as regras, a primeira regra encontrada sempre têm precedência sobre as demais. Exclua uma regra removendo seu valor.';
$string['for1day'] = 'Por um dia';
$string['for1month'] = 'Por um mês';
$string['for1week'] = 'Por uma semana';
$string['for3days'] = 'Por 3 dias';
$string['forever'] = 'Para sempre';
$string['give'] = 'dar';
$string['infos'] = 'Informação';
$string['invalidxp'] = 'Valor de XP inválido';
$string['keeplogs'] = 'Manter logs';
$string['ladder'] = 'Ranking';
$string['level'] = 'Nível';
$string['levelbadges'] = 'Emblemas de nível';
$string['levelbadgesformhelp'] = 'Nome dos arquivos [nivel].[extensão do arquivo], por exemplo: 1.png, 2.jpg, etc... O tamanho recomendado da imagem é 100x100.';
$string['levelcount'] = 'Quantidade de níveis';
$string['leveldesc'] = 'Descrição do nível';
$string['levels'] = 'Níveis';
$string['levelswillbereset'] = 'Atenção! Ao salvar este formulário serão recalculados os níveis de todos!';
$string['levelup'] = 'Level up!';
$string['levelx'] = 'Nível #{$a}';
$string['logging'] = 'Logging';
$string['navinfos'] = 'Informações';
$string['navladder'] = 'Ranking';
$string['navlevels'] = 'Níveis';
$string['navlog'] = 'Log';
$string['navreport'] = 'Relatório';
$string['navrules'] = 'Regras';
$string['navsettings'] = 'Configurações';
$string['navvisuals'] = 'Visuais';
$string['pluginname'] = 'Level up!';
$string['progress'] = 'Progresso';
$string['property:action'] = 'Ação do evento';
$string['property:component'] = 'Componente do evento';
$string['property:crud'] = 'CRUD do evento';
$string['property:eventname'] = 'Nome do evento';
$string['property:target'] = 'Alvo do evento';
$string['participatetolevelup'] = 'Participe do curso para ganhar pontos de experiência e passar de nível!';
$string['rank'] = 'Posição';
$string['reallyresetdata'] = 'Deseja realmente resetar os níveis e pontos de experiência de todos neste curso?';
$string['resetcoursedata'] = 'Resetar os dados do curso';
$string['rule'] = 'Regra';
$string['rule:contains'] = 'contêm';
$string['rule:eq'] = 'é igual a';
$string['rule:eqs'] = 'é estritamente igual a';
$string['rule:gt'] = 'é maior que';
$string['rule:gte'] = 'é maior ou igual a';
$string['rule:lt'] = 'é menor que';
$string['rule:lte'] = 'é menor ou igual a';
$string['rule:regex'] = 'matches the regex';
$string['rulesformhelp'] = '<p>Este plugin é feito usando os eventos para atribuir pontos de experiência por ações executadas pelos estudantes. Você pode usar o formulário abaixo para adicionar suas próprias regras e visualizar as padrões.</p>
<p>É aconselhável verificar o <a href="{$a->log}">log</a> do plugin para identificar quais eventos são disparados conforme você executa ações no curso, e também ler mais sobre esses eventos: <a href="{$a->list}">lista de todos os eventos</a>, <a href="{$a->doc}">documentação para desenvolvedores</a>.</p>
<p>Finalmente, por favor, note que o plugin sempre ignora:
<ul>
    <li>Ações executadas por administradores, visitantes ou usuários não logados.</li>
    <li>Ações executadas por usuários que não tem a capacidade <em>block/xp:earnxp</em>.</li>
    <li>Ações repetidas em um curto espaço de tempo, para prevenir trapaça.</li>
    <li>E os eventos que o nível educacional não é igual a <em>Participar</em>.</li>
</ul>
</p>';
$string['value'] = 'Valor';
$string['valuessaved'] = 'Os valores foram salvos com sucesso.';
$string['viewtheladder'] = 'Visualizar o ranking';
$string['updateandpreview'] = 'Atualizar e visualizar';
$string['usealgo'] = 'Usar o algoritmo';
$string['usecustomlevelbadges'] = 'Usar emblemas de níveis customizados';
$string['usecustomlevelbadges_help'] = 'Quando setado para sim, você deve fornecer uma imagem para cada nível.';
$string['when'] = 'Quando';
$string['xp'] = 'Pontos de experiência';
$string['xp:addinstance'] = 'Adicionar um novo bloco de XP';
$string['xp:earnxp'] = 'Ganhando pontos de experiência';
$string['xprequired'] = 'XP necessário';
$string['xpgaindisabled'] = 'Ganho de XP desabilitado';
$string['youreachedlevela'] = 'Você alcançou o nível {$a}!';
