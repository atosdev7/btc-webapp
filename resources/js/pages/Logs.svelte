<script lang="ts">
    import { onMount } from 'svelte';
    import { type BreadcrumbItem } from '@/types';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import * as Card from '@/components/ui/card/index.js';
    import { useForm, router } from '@inertiajs/svelte';
    import DeviceSelecter from '@/components/DeviceSelecter.svelte';
    import LogChart from '@/components/LogChart.svelte';
    import * as Popover from '@/components/ui/popover/index';
    import Button from '@/components/ui/button/button.svelte';
    import { CalendarIcon } from 'lucide-svelte';
    import { Calendar } from '@/components/ui/calendar/index.js';
    import { type DateValue, CalendarDate, DateFormatter, getLocalTimeZone, toCalendarDate } from '@internationalized/date';
    import { cn } from '@/lib/utils.js';
    import * as Select from '@/components/ui/select/index';
    import { LoaderCircle } from 'lucide-svelte';
    import { ScrollArea } from "@/components/ui/scroll-area/index.js";

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Tank Logs',
            href: '/logs',
        },
    ];

    interface Log {
        id: number;
        time: string;
        current_temp: any;
        solenoid: any;
    }

    interface Props {
        devices: { id: number; name: string; board_id: number; tank_count: number }[];
        selectedDeviceId?: number | null;
        selectedTankId?: number | null;
        selectedDate?: string | null;
        logs: Log[];
        error: string | null;
    }

    let { error, devices, selectedDeviceId, selectedTankId, selectedDate, logs } = $props();

    const df = new DateFormatter('en-US', {
        dateStyle: 'medium',
    });

    const sDate = new Date(selectedDate);
    let dateValue = $state<DateValue>(new CalendarDate(sDate.getFullYear(), sDate.getMonth() + 1, sDate.getDate()));

    let tank_count = $derived(devices.find((device) => device.id == selectedDeviceId)?.tank_count || 0);
    let isLoading = $state(false); // Track loading state

    function handleDeviceSelect(deviceId: number): void {
        router.get(route('logs.index'), { device_id: deviceId, tank_id: 1, date: selectedDate });
    }

    async function handleLoadButtonClick(): Promise<void> {
        isLoading = true; // Set loading state to true
        try {
            const dateStr = dateValue.year + "-" + dateValue.month + "-" + dateValue.day;
            await router.get(route('logs.index'), { device_id: selectedDeviceId, tank_id: selectedTankId, date: dateStr });
        } finally {
            isLoading = false; // Reset loading state
        }
    }

    // Fetch devices from the API
    onMount(async () => {});
</script>

<svelte:head>
    <title>Tank Logs</title>
</svelte:head>

{#snippet deviceSelecter()}
    <DeviceSelecter {devices} {selectedDeviceId} onSelect={handleDeviceSelect} />
{/snippet}

<AppLayout {breadcrumbs} {deviceSelecter}>
    {#if error}
        <!-- Show error message -->
        <div class="alert alert-error p-4 rounded-lg bg-red-100 text-red-800 m-4">
            <p>{error}</p>
        </div>  
    {:else}
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 w-screen sm:w-full md:w-full">
            <Card.Root
                data-x-chunk-name="dashboard-06-chunk-0"
                data-x-chunk-description="A list of products in a table with actions. Each row has an image, name, status, price, total sales, created at and actions."
            >
                <Card.Header class="flex flex-row items-center justify-between gap-4 flex-wrap">
                    <Card.Title class="gap-2">Logs</Card.Title>
                    <div class="flex gap-2  flex-wrap">
                        <Select.Root type="single" name="favoriteFruit" bind:value={selectedTankId}>
                            <Select.Trigger class="w-[180px]">
                                Tank{selectedTankId}
                            </Select.Trigger>
                            <Select.Content>
                                {#each { length: tank_count }, tank_index}
                                    <Select.Item value={String(tank_index + 1)} label="Tank {tank_index + 1}">Tank{tank_index + 1}</Select.Item>
                                {/each}
                            </Select.Content>
                        </Select.Root>
                        <Popover.Root>
                            <Popover.Trigger>
                                {#snippet child({ props })}
                                    <Button
                                        variant="outline"
                                        class={cn('w-[180px] justify-start text-left font-normal', !dateValue && 'text-muted-foreground')}
                                        {...props}
                                    >
                                        <CalendarIcon class="mr-2 size-4" />
                                        {dateValue ? df.format(dateValue.toDate(getLocalTimeZone())) : 'Select a date'}
                                    </Button>
                                {/snippet}
                            </Popover.Trigger>
                            <Popover.Content class="w-auto p-0">
                                <Calendar bind:value={dateValue} type="single" initialFocus />
                            </Popover.Content>
                        </Popover.Root>
                        <Button onclick={handleLoadButtonClick} disabled={isLoading} class="w-[100px]">
                            {#if isLoading}
                                <LoaderCircle class="animate-spin mr-2" />
                                Loading...
                            {:else}
                                Load
                            {/if}
                        </Button>
                    </div>
                </Card.Header>
                <Card.Content class="flex container flex-1 w-full">
                    <div class="rounded-md border">
                        <LogChart data={logs}></LogChart>
                    </div>
                </Card.Content>
            </Card.Root>
        </div>
    {/if}
</AppLayout>
