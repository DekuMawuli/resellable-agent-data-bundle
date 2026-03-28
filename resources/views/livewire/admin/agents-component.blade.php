<div class="row crud-shell">
    <div class="col-12">
        <div class="card crud-card crud-scroll">
            <div class="card-body">
                <h4 class="card-title">All Agents</h4>
                <div class="form-group">
                    <input type="text" wire:model.live="query" class="form-control" placeholder="Search Agent Name....">
                </div>
                @include("partials.alerts_inc")
                <div class="table-responsive">
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
                                        class="btn btn-danger btn-sm">
                                   <i class="fas fa-trash-alt" aria-hidden="true"></i> Delete
                                </button>
                                @if($agent->agent_status != "active")
                                    <button wire:click="activateAcc('{{ $agentCode }}')" class="btn btn-dark btn-sm">
                                        <i class="fas fa-user-check" aria-hidden="true"></i> Activate Account
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
                    </div>
                    <div class="form-group">
                        <button wire:click="updateAgentWallet" class="btn btn-info">Load Wallet</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
