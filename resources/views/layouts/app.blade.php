<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
    <x-flash-banner />
    <x-temp-password-banner />
        {{ $slot }}
    </flux:main>
    
    <footer class="w-full h-10 bg-zinc-800 m-0 p-0 text-center flex align-center justify-center items-center" style="position: fixed; bottom: 0; left: 0;"> 
        <div class="flex w-full align-center justify-center items-center gap-1">
            <flux:icon.code-bracket />
            <flux:heading>Developed by the interns of</flux:heading><flux:heading>
                <flux:tooltip toggleable>
                    <flux:button class="p-0 m-0" size="sm" variant="ghost" icon:trailing="information-circle"> Baguio Central University </flux:button>
                    <flux:tooltip.content class="max-w-[10rem] space-y-2">
                        <p><flux:link href="https://www.facebook.com/erick.gaceta.3">Erick Gaceta</flux:link></p>
                        <p><flux:link href="https://www.facebook.com/keita.pendragon.9">Ryan Joseph Fagyan</flux:link></p>
                        <p><flux:link href="https://www.facebook.com/dcabz27">Derrik Cabanilla</flux:link></p>
                    </flux:tooltip.content>
                </flux:tooltip>
            </flux:heading>
        </div>
    </footer>
</x-layouts::app.sidebar>