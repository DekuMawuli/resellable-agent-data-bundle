<form action="{{ route("agent.deposit") }}" method="POST">
                      @csrf
                      <div class="form-group">
                          <label for="">Enter Amount (GHS)</label>
                          <input type="number" step="0.01" class="form-control" x-model="amount" min="1" name="amount" placeholder="eg. 5.00, 10.00">
                      </div>
                      {{-- <div class="form-group">
                          <button type="submit" class="btn btn-info btn-block">Manual Deposit</button>


                      </div> --}}
                  </form>

                  <hr>
