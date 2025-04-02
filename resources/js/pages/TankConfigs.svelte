<script lang="ts">
    import { onMount } from 'svelte';
    import { type BreadcrumbItem } from '@/types';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import * as Card from '@/components/ui/card/index.js';
    import * as Table from '@/components/ui/table/index.js';
    import { Badge } from '@/components/ui/badge/index.js';
    import * as Dialog from '@/components/ui/dialog/index.js';
    import { Label } from '@/components/ui/label/index.js';
    import { Input } from '@/components/ui/input/index.js';
    import { Button } from '@/components/ui/button/index.js';
    import * as Select from '@/components/ui/select/index.js';
    import { Edit } from 'lucide-svelte';
    import { useForm, router } from '@inertiajs/svelte';
    import DeviceSelecter from '@/components/DeviceSelecter.svelte';

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Tank Settings',
            href: '/tankconfigs',
        },
    ];

    let { error , devices, selectedDeviceId, showDeviceCombobox, tankConfigs} = $props();

    let isEditDialogOpen = $state(false);

    const editConfigForm = useForm<{
        id: number;
        device_id: number;
        tank_id: number;
        control_mode: number;
        target_temp: number;
        pt100_cal: number;
        degree_per_day: number;
    }>({
        id: 0,
        device_id: 0,
        tank_id: 1,
        control_mode: 0,
        target_temp: 0,
        pt100_cal: 0,
        degree_per_day: 0,
    });

    const controlModes = [
        { value: 0, label: 'Stop' },
        { value: 1, label: 'Normal' },
        { value: 2, label: 'Crash' },
    ];
    let triggerContent = $derived(controlModes.find((mode) => mode.value == $editConfigForm.control_mode)?.label ?? 'Select a control mode');

    function submitEditDevice(event: Event) {
        event.preventDefault();
        $editConfigForm.put(route('tankconfigs.update', $editConfigForm.id), {
            onSuccess: () => {
                isEditDialogOpen = false; // Close the modal
            },
        });
    }

    function openEditModal(tankConfig: { id: number; device_id: number; tank_id: number; control_mode: number; target_temp: number; pt100_cal: number; degree_per_day: number }): void {
        $editConfigForm.id = tankConfig.id;
        $editConfigForm.device_id = tankConfig.device_id;
        $editConfigForm.tank_id = tankConfig.tank_id;
        $editConfigForm.control_mode = tankConfig.control_mode;
        $editConfigForm.target_temp = tankConfig.target_temp;
        $editConfigForm.pt100_cal = tankConfig.pt100_cal;
        $editConfigForm.degree_per_day = tankConfig.degree_per_day;

        isEditDialogOpen = true; // Open the modal
    }

    function handleDeviceSelect(deviceId: number): void {
        router.get(route('tankconfigs.index'), {device_id: deviceId });
    }

    // Fetch devices from the API
    onMount(async () => {});
</script>

<svelte:head>
    <title>Tank Settings</title>
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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Dialog.Root bind:open={isEditDialogOpen}>
                <Dialog.Content>
                    <form onsubmit={submitEditDevice} class="space-y-6">
                        <input type="hidden" name="id" bind:value={$editConfigForm.id} />
                        <Dialog.Header>
                            <Dialog.Title>Tank{$editConfigForm.tank_id} &nbsp; Config</Dialog.Title>
                        </Dialog.Header>
                        <div class="grid gap-4 py-4">
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="control_mode" class="text-right">Control <br>Mode</Label>
                                <Select.Root type="single" name="control_mode" bind:value={$editConfigForm.control_mode}>
                                    <Select.Trigger  class="col-span-3">
                                        {triggerContent}
                                    </Select.Trigger>
                                    <Select.Content>
                                        {#each controlModes as mode}
                                            <Select.Item value={String(mode.value)} label={String(mode.label)}>{mode.label}</Select.Item>
                                        {/each}
                                    </Select.Content>
                                </Select.Root>                                
                            </div>
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="edit-board-id" class="text-right">Target <br>Temp</Label>
                                <Input id="edit-board-id" name="board_id" bind:value={$editConfigForm.target_temp} class="col-span-3" />
                            </div>
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="edit-tank-count" class="text-right">PT100 <br>Cal</Label>
                                <Input id="edit-tank-count" name="tank_count" bind:value={$editConfigForm.pt100_cal} class="col-span-3" />
                            </div>
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="edit-tank-count" class="text-right">Degrees <br>per Day</Label>
                                <Input id="edit-tank-count" name="tank_count" bind:value={$editConfigForm.degree_per_day} class="col-span-3" />
                            </div>
                        </div>
                        <Dialog.Footer>
                            <Button type="submit">Save Changes</Button>
                        </Dialog.Footer>
                    </form>
                </Dialog.Content>
            </Dialog.Root>
            <Card.Root
                data-x-chunk-name="dashboard-06-chunk-0"
                data-x-chunk-description="A list of products in a table with actions. Each row has an image, name, status, price, total sales, created at and actions."
            >
                <Card.Content>
                    {#if tankConfigs.length === 0}
                        <p>No configs found.</p>
                    {:else}
                        <Table.Root>
                            <Table.Header>
                                <Table.Row>
                                    <Table.Head class="hidden">ID</Table.Head>
                                    <Table.Head>Tank ID</Table.Head>
                                    <Table.Head>Control<br />Mode</Table.Head>
                                    <Table.Head>Target<br> Temp</Table.Head>
                                    <Table.Head>Pt100<br>Cal</Table.Head>
                                    <Table.Head>Degrees<br>per Day</Table.Head>
                                    <Table.Head>Action</Table.Head>
                                </Table.Row>
                            </Table.Header>
                            <Table.Body>
                                {#each tankConfigs as tankConfig}
                                    <Table.Row>
                                        <Table.Cell class="hidden font-medium">{tankConfig.id}</Table.Cell>
                                        <Table.Cell>{tankConfig.tank_id}</Table.Cell>
                                        <Table.Cell>
                                            <Badge
                                                class={tankConfig.control_mode === 0
                                                    ? 'bg-red-500 text-white'
                                                    : tankConfig.control_mode === 1
                                                      ? 'bg-green-500 text-white'
                                                      : 'bg-orange-500 text-white'}
                                            >
                                                {tankConfig.control_mode === 0 ? 'Stop' : tankConfig.control_mode === 1 ? 'Normal' : 'Crash'}
                                            </Badge>
                                        </Table.Cell>
                                        <Table.Cell>{tankConfig.target_temp.toFixed(2)}</Table.Cell>
                                        <Table.Cell>{tankConfig.pt100_cal.toFixed(2)}</Table.Cell>
                                        <Table.Cell>{tankConfig.degree_per_day}</Table.Cell>
                                        <Table.Cell>
                                            <button onclick={() => openEditModal(tankConfig)} class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-label="Edit">
                                                <Edit class="h-4 w-4" />
                                            </button>
                                        </Table.Cell>
                                    </Table.Row>
                                {/each}
                            </Table.Body>
                        </Table.Root>
                    {/if}
                </Card.Content>
            </Card.Root>
        </div>
    {/if}
</AppLayout>
