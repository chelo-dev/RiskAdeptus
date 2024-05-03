<?php

namespace App\Http\Controllers\administration\User;

use App\Helpers\SharedFunctionsHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    protected $shared;

    // Autorización
    public function __construct(SharedFunctionsHelpers $shared)
    {
        $this->shared = $shared;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->shared->getUserStatus();
            return $next($request);
        });
    }

    /*
    |----------------------------------------------------------------------------------------------------
    |   Administración cuenta de usuario
    |----------------------------------------------------------------------------------------------------
    */

    public function getAccount($uuid)
    {
        # Politica para saber si el usuario cuenta con los permisos deacuerdo a su rol asignado
        // Gate::authorize('getAccount');

        try {
            $account = User::where('uuid', $uuid)->firstOrFail();

            return view('pages.account.user.account', compact('account'));
        } catch (Exception $error) {
            return redirect()->route('dashboard')->with('error', $error->getMessage());
        }
    }

    public function editAccount(Request $request, $uuid)
    {
        # Politica para saber si el usuario cuenta con los permisos deacuerdo a su rol asignado
        // Gate::authorize('editAccount');

        $this->validate($request, [
            'name'  => 'nullable|min:3|max:255|regex:/^[\pL\s\-]+$/u',
            'phone' => 'nullable|regex:/^\(\d{3}\) \d{3}-\d{4}$/',
            'notes' => 'nullable|min:5|max:2147483647',
        ]);

        try {
            $account = User::where('uuid', $uuid)->firstOrFail();
            $account->name = $request->name;
            $account->phone = $request->phone;
            $account->notes = $request->notes;
            $account->save();

            return $this->shared->sendResponse($account, 'Cuenta Actualizada con éxito!');
        } catch (Exception $error) {
            return $this->shared->sendError($error->getMessage(), 'Sucesio un problema intente');
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    |   Administración de usuarios
    |----------------------------------------------------------------------------------------------------
    */

    public function listUser(User $users)
    {
        # Politica para saber si el usuario cuenta con los permisos deacuerdo a su rol asignado
        // Gate::authorize('listUser');

        try {
            $users = User::with('roles')
                ->select('users.*')
                ->orderByDesc('id');

                if (request()->ajax()) {
                    return datatables()->of($users)
                        ->addColumn('options', function ($user) {
                            return view('pages.system.users.shared.options', ['uuid' => $user->uuid]);
                        })
                        ->addColumn('is_active', function($user) {
                            return $user->is_active ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
                        })
                        ->addColumn('role', function($user) {
                            return $user->roles->first()->name ?? 'N/A';
                        })
                        ->addColumn('created_at', function ($user) {
                            return date('d-m-Y H:i:s', strtotime($user->created_at));
                        })
                        ->rawColumns(['options', 'is_active'])
                        ->toJson();
                }

            return view('pages.system.users.list');
        } catch (Exception $error) {
            return redirect()->route('dashboard')->with('error', $error->getMessage());
        }
    }

    public function deatailUser($uuid)
    {
        # Politica para saber si el usuario cuenta con los permisos deacuerdo a su rol asignado
        // Gate::authorize('deatailUser');

        try {
            $user = User::where('uuid', $uuid)->firstOrFail();

            return $this->shared->sendResponse($user, 'Deatail user.');
        } catch (Exception $error) {
            return $this->shared->sendError($error->getMessage());
        }
    }

    public function deleteUser(Request $request)
    {
        # Politica para saber si el usuario cuenta con los permisos deacuerdo a su rol asignado
        // Gate::authorize('deleteUser');

        $this->validate($request, [
            'uuid' => 'required|string|exists:users,uuid',
        ]);

        try {
            $user = User::findOrFail($request->uuid);

            return $this->shared->sendResponse($user, 'Usuario eliminado con exito.');
        } catch (Exception $error) {
            return $this->shared->sendError($error->getMessage(), 'Usuario eliminado con exito.', 404);
        }
    }
}
