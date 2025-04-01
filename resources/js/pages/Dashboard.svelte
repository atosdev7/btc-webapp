<script lang="ts">
    import PlaceholderPattern from '@/components/PlaceholderPattern.svelte';
    import * as Card from '@/components/ui/card/index';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import { type BreadcrumbItem } from '@/types';
    import { Badge } from "@/components/ui/badge/index.js";
    import { tick } from 'svelte';

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
        },
    ];

    export let error: string | null = null;
    export let devices: any[] = [];
    export let selectedDeviceId: number | null = null;
    export let showDeviceCombobox: boolean = true;
    export let tankStates: { id: number; device_id: number; tank_id: number; current_temp: number; solenoid: boolean; max_rtd: number; max_status: number; sol_time: number; }[] = []; 

    // Utility function to format seconds into hours:minutes:seconds
    function formatTime(seconds: number): string {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hrs.toString().padStart(2, '0')}h ${mins.toString().padStart(2, '0')}m ${secs.toString().padStart(2, '0')}s`;
    }    
</script>

<svelte:head>
    <title>Dashboard</title>
</svelte:head>

<AppLayout {breadcrumbs} showDeviceCombobox={showDeviceCombobox} devices={devices} selectedDeviceId={selectedDeviceId}>
    {#if error}
        <!-- Show error message -->
        <div class="alert alert-error p-4 rounded-lg bg-red-100 text-red-800 m-4">
            <p>{error}</p>
        </div>
    {:else}
        <div class="flex flex-1 flex-col gap-4 p-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                {#each tankStates as tankState}
                    <Card.Root>
                        <Card.Header class="flex flex-row items-start justify-between">
                            <Card.Title>Tank {tankState.tank_id}</Card.Title>
                        </Card.Header>
                        <Card.Content>
                            <div class="flex flex-row items-start justify-between"><p>Current Temp:</p><span>{tankState.current_temp.toFixed(2)} °F</span></div>
                            <div class="flex flex-row items-start justify-between"><p>Target Temp:</p><span>{tankState.current_temp.toFixed(2)} °F</span></div>
                            <div class="flex flex-row items-start justify-between"><p>Solenoid:</p><Badge class="{tankState.solenoid ? 'bg-green-500 text-white' : 'bg-orange-500 text-white'}">{tankState.solenoid ? 'On' : 'Off'}</Badge></div>
                            <div class="flex flex-row items-start justify-between"><p>Sol Time:</p><span>{formatTime(tankState.sol_time)}</span></div>
                        </Card.Content>
                    </Card.Root>
                {/each}
            </div>
        </div>
    {/if}
</AppLayout>
