<header class="navbar" style="height: 70px; background: #ffffff; border-bottom: 1px solid #e8edf5; display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 90; box-sizing: border-box;">
    
    <div>
        <h1 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0 0 2px 0; letter-spacing: -0.2px;">Dashboard Owner </h1>
        <p style="font-size: 11px; font-weight: 600; color: #778195; margin: 0;">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    <div style="display: flex; align-items: center; gap: 12px;">
        
        
       

        <div style="width: 1px; height: 24px; background: #e8edf5; margin: 0 4px;"></div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; background: #006B3F; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px;">
                OW
            </div>
        </div>

    </div>

</header>