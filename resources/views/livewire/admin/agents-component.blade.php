<div class="row crud-shell">
    <div class="col-12">
        <div class="card crud-card crud-scroll">
            <div class="card-body">
                <h4 class="card-title">All Agents</h4>
                <div class="form-group">
                    <input type="text" wire:model.live="query" class="form-control" placeholder="Search Agent Name....">
                    <div class="small text-muted mt-2 d-none align-items-center gap-2" wire:loading.flex wire:target="query">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Refreshing agents...
                    </div>
                </div>
                @include("partials.alerts_inc")
                <div class="table-responsive" wire:loading.class="opacity-50" wire:target="query">
                <table class="table table-striped table-inverse table-responsive-sm">
            <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Balance</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach($allAgents as $agent)
                    @php
                        $agentCode = $agent->code;
                    @endphp
                    <tr>
                        <td>{{ $agent->name }} </td>

                        <td>{{ $agent->phone }}</td>
                        <td>{{ $agent->balance }}</td>
                        <td>
                            <div class="action-group">
                            @if(!blank($agentCode))
                                <a href="{{ route("root.agent_detail", ['code' => $agentCode]) }}" class="btn btn-success btn-sm">
                                   <i class="fas fa-eye" aria-hidden="true"></i> View
                                </a>
                                <button
                                        type="button"
                                        wire:confirm.prompt="Are you sure?\n\nType YES to confirm|YES"
                                        wire:click="deleteAcc('{{ $agentCode }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="deleteAcc('{{ $agentCode }}')"
                                        class="btn btn-danger btn-sm">
                                   <span wire:loading wire:target="deleteAcc('{{ $agentCode }}')" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                   <i class="fas fa-trash-alt" aria-hidden="true" wire:loading.remove wire:target="deleteAcc('{{ $agentCode }}')"></i>
                                   <span wire:loading.remove wire:target="deleteAcc('{{ $agentCode }}')"> Delete</span>
                                   <span wire:loading wire:target="deleteAcc('{{ $agentCode }}')">Deleting...</span>
                                </button>
                                @if($agent->agent_status != "active")
                                    <button
                                        type="button"
                                        wire:click="activateAcc('{{ $agentCode }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="activateAcc('{{ $agentCode }}')"
                                        class="btn btn-dark btn-sm"
                                    >
                                        <span wire:loading wire:target="activateAcc('{{ $agentCode }}')" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        <i class="fas fa-user-check" aria-hidden="true" wire:loading.remove wire:target="activateAcc('{{ $agentCode }}')"></i>
                                        <span wire:loading.remove wire:target="activateAcc('{{ $agentCode }}')"> Activate Account</span>
                                        <span wire:loading wire:target="activateAcc('{{ $agentCode }}')">Activating...</span>
                                    </button>
                                @endif
                            @else
                                <span class="badge bg-warning">Missing Agent Code</span>
                            @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" wire:ignore.self id="showTopUpModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Load Wallet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="number" step="0.01" wire:model.live="amount" class="form-control">
                        <div class="small text-muted mt-2 d-none align-items-center gap-2" wire:loading.flex wire:target="amount">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Checking amount...
                        </div>
                    </div>
                    <div class="form-group">
                        <button
                            type="button"
                            wire:click="updateAgentWallet"
                            wire:loading.attr="disabled"
                            wire:target="updateAgentWallet"
                            class="btn btn-info"
                        >
                            <span wire:loading wire:target="updateAgentWallet" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            <span wire:loading.remove wire:target="updateAgentWallet">Load Wallet</span>
                            <span wire:loading wire:target="updateAgentWallet">Loading...</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
