@if(session()->has('message'))
    <div 
        id="flashAlert"
        class="alert alert-success alert-dismissible fade show text-white shadow-lg"
        role="alert"
        style="
            position: fixed;
            top: 20px;
            right: 20px;
            width: 350px;      /* Custom width */
            z-index: 1055;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-weight: 500;
        "
    >
        <button 
            type="button" 
            class="btn-close btn-close-white" 
            data-bs-dismiss="alert" 
            aria-label="Close">
        </button>

        {{ session('message') }}
    </div>

    
@endif

@if(session()->has('error'))
    <div 
        id="flashAlertError"
        class="alert alert-danger alert-dismissible fade show text-white shadow-lg"
        role="alert"
        style="
            position: fixed;
            top: 20px;
            right: 20px;
            width: 350px;
            z-index: 1055;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-weight: 500;
        "
    >
        <button 
            type="button" 
            class="btn-close btn-close-white" 
            data-bs-dismiss="alert" 
            aria-label="Close">
        </button>

        {{ session('error') }}
    </div>
@endif