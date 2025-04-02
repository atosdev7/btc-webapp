<script lang="ts">
    import * as Card from '@/components/ui/card/index';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import { type BreadcrumbItem } from '@/types';
    import { Badge } from "@/components/ui/badge/index.js";
    import DeviceSelecter from '@/components/DeviceSelecter.svelte';
    import { router } from '@inertiajs/svelte';
    import { onDestroy } from 'svelte';

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
        },
    ];

    export let error: string | null = null;
    export let devices: { id: number; name: string; board_id: number; tank_count: number; }[] = [];
    export let selectedDeviceId: number | null = null;
    export let tankStates: { id: number; device_id: number; tank_id: number; current_temp: number; solenoid: boolean; max_rtd: number; max_status: number; sol_time: number; }[] = []; 
    export let tankConfigs: { id: number; device_id: number; tank_id: number; control_mode: number; target_temp: number; pt100_cal: number; degree_per_day: number; }[] = [];

    const mergedData = tankStates.map((tankState) => {
        const matchingConfig = tankConfigs.find((config) => config?.tank_id === tankState?.tank_id) || { control_mode: null, target_temp: null, pt100_cal: null, degree_per_day: null };
        return {
            ...tankState,
            control_mode: matchingConfig.control_mode ?? null,
            target_temp: matchingConfig.target_temp ?? null,
            pt100_cal: matchingConfig.pt100_cal ?? null,
            degree_per_day: matchingConfig.degree_per_day ?? null,
        };
    }); 
    // Utility function to format seconds into hours:minutes:seconds
    function formatTime(seconds: number): string {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hrs.toString().padStart(2, '0')}h ${mins.toString().padStart(2, '0')}m ${secs.toString().padStart(2, '0')}s`;
    }

    function handleDeviceSelect(deviceId: number): void {
        router.get(route('dashboard'), {device_id: deviceId });
    }    

    // Refresh the page every 10 seconds
    const interval = setInterval(() => {
        router.get(route('dashboard'), { device_id: selectedDeviceId });
    }, 10000);

    // Cleanup the interval when the component is destroyed
    onDestroy(() => {
        clearInterval(interval);
    });    
</script>

<svelte:head>
    <title>Dashboard</title>
</svelte:head>

{#snippet deviceSelecter()}
<DeviceSelecter
    {devices}
    {selectedDeviceId}
    onSelect={handleDeviceSelect}
/>
{/snippet}

<AppLayout {breadcrumbs} {deviceSelecter}>
    {#if error}
        <!-- Show error message -->
        <div class="alert alert-error p-4 rounded-lg bg-red-100 text-red-800 m-4">
            <p>{error}</p>
        </div>
    {:else}
        <div class="flex flex-1 flex-col gap-4 p-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                {#each mergedData as tank}
                    <Card.Root>
                        <Card.Header class="flex flex-row items-start justify-between">
                            <Card.Title>Tank {tank.tank_id}</Card.Title>
                        </Card.Header>
                        <Card.Content>
                            <div class="flex flex-row items-start justify-between">
                                <p>Control Mode:</p>
                                {#if tank.control_mode === 0}
                                    <Badge class="bg-red-500 text-white">Stop</Badge>
                                {:else if tank.control_mode === 1}
                                    <Badge class="bg-green-500 text-white">Normal</Badge>
                                {:else if tank.control_mode === 2}
                                    <Badge class="bg-blue-500 text-white">Crash</Badge>
                                {/if}
                            </div> 
                            <div class="flex flex-row items-start justify-between"><p>Current Temp:</p><span>{tank.current_temp.toFixed(2)} °F</span></div>
                            <div class="flex flex-row items-start justify-between"><p>Target Temp:</p><span>{(tank.target_temp ?? 0).toFixed(2)} °F</span></div>
                            <div class="flex flex-row items-start justify-between"><p>Solenoid:</p><Badge class="{tank.solenoid ? 'bg-green-500 text-white' : 'bg-orange-500 text-white'}">{tank.solenoid ? 'On' : 'Off'}</Badge></div>
                            <div class="flex flex-row items-start justify-between"><p>Sol Time:</p><span>{formatTime(tank.sol_time)}</span></div>
                        </Card.Content>
                    </Card.Root>
                {/each}
            </div>
        </div>
    {/if}
</AppLayout>
