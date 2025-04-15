<?php
namespace App\Domain\Team\Actions;

use App\Domain\Invitation\Models\TeamInvitation;
use App\Domain\Team\Models\Team;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetTeamsAndSearchableAction
{
    /**
     * Obtém os membros e convites do time do usuário logado
     *
     * @param int $perPage Número de itens por página
     * @param int $page Número da página atual
     * @param string $searchTerm Termo de pesquisa opcional
     * @return LengthAwarePaginator
     */
    public static function execute(int $perPage = 10, int $page = 1, string $searchTerm = ''): LengthAwarePaginator
    {
        // Obter o ID do time do usuário logado
        $teamId = DB::table('teams')
            ->where('owner_id', auth()->user()->id)
            ->value('id');

        if (empty($teamId)) {
            // Se não houver time, retornamos uma paginação vazia
            return new LengthAwarePaginator(
                [], 0, $perPage, $page, ['path' => request()->url()]
            );
        }

        // Buscar membros ativos do time
        $memberQuery = DB::table('users AS u')
            ->select([
                DB::raw('u.id as id'),
                'u.name',
                'u.email',
                DB::raw("'ativo' as status"),
                't.billing_type',
                't.billing_rate',
                't.cost_rate' // Adicionado o campo de valor cobrado
            ])
            ->join('teams AS t', 'u.id', '=', 't.user_id')
            ->where('t.id', '=', $teamId);

        // Buscar convites pendentes do time
        $invitationQuery = DB::table('team_invitations AS ti')
            ->select([
                DB::raw('ti.id as id'),
                DB::raw('NULL as name'),
                'ti.email',
                DB::raw("'pendente' as status"),
                'ti.billing_type',
                'ti.billing_rate',
                'ti.cost_rate' // Adicionado o campo de valor cobrado
            ])
            ->where('ti.team_id', '=', $teamId)
            ->where('ti.status', '=', 1);

        // Aplicar filtro de pesquisa, se fornecido
        if (!empty($searchTerm)) {
            $memberQuery->where(function ($query) use ($searchTerm) {
                $query->where('u.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('u.email', 'like', '%' . $searchTerm . '%');
            });

            $invitationQuery->where('ti.email', 'like', '%' . $searchTerm . '%');
        }

        // Combinar as consultas com union
        $unionQuery = $memberQuery->unionAll($invitationQuery);

        // Obter o total de registros para paginação
        $total = DB::query()
            ->fromSub($unionQuery, 'combined_results')
            ->count();

        // Executar a consulta com paginação
        $results = DB::query()
            ->fromSub($unionQuery, 'combined_results')
            ->orderBy('email')
            ->forPage($page, $perPage)
            ->get();

        // Criar paginador manualmente
        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'pageName' => 'page'
            ]
        );
    }
}
