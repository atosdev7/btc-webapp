<script lang="ts">
    import { onMount } from 'svelte';
    import AppLayout from '@/layouts/AppLayout.svelte';
    import { type BreadcrumbItem } from '@/types';
    import { Badge } from '@/components/ui/badge/index.js';
    import { Button } from '@/components/ui/button/index.js';
    import * as Card from '@/components/ui/card/index.js';
    import * as Table from '@/components/ui/table/index.js';
    import * as DropdownMenu from '@/components/ui/dropdown-menu/index.js';
    import * as Dialog from '@/components/ui/dialog/index.js';
    import { Input } from '@/components/ui/input/index.js';
    import { Label } from '@/components/ui/label/index.js';
    import { useForm, router } from '@inertiajs/svelte';
    import Ellipsis from 'lucide-svelte/icons/ellipsis';
    import CirclePlus from 'lucide-svelte/icons/circle-plus';

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Devices',
            href: '/devices',
        },
    ];
    export let devices: { id: number; name: string; board_id: number; tank_count: number }[] = [];

    // let devices: { id: number; name: string; board_id: string }[] = []; // Array to store devices data
    let isAddDialogOpen = false;
    let isEditDialogOpen = false;

    const addDeviceForm = useForm({
        name: '',
        board_id: '',
        tank_count: 8,
    });

    const editDeviceForm = useForm<{ id: number; name: string; board_id: string; tank_count: number }>({
        id: 0,
        name: '',
        board_id: '',
        tank_count: 8,
    });

    const submitAddDevice = (e: Event) => {
        e.preventDefault();
        $addDeviceForm.post(route('devices.store'), {
            preserveScroll: true,
            onSuccess: () => {
                // Close the modal
                isAddDialogOpen = false;
            },
        });
    };

    function submitEditDevice(event: Event) {
        event.preventDefault();
        $editDeviceForm.put(route('devices.update', $editDeviceForm.id), {
            onSuccess: () => {
                isEditDialogOpen = false; // Close the modal
            },
        });
    }

    function deleteDevice(deviceId: number) {
        if (confirm('Are you sure you want to delete this device?')) {
            router.delete(route('devices.destroy', deviceId), {
                onSuccess: () => {
                    alert('Device deleted successfully.');
                },
            });
        }
    }

    function openEditModal(device: { id: number; name: string; board_id: number; tank_count: number }) {
        $editDeviceForm.id = device.id;
        $editDeviceForm.name = device.name;
        $editDeviceForm.board_id =`BTC-${String(device.board_id).padStart(5, '0')}`;
        $editDeviceForm.tank_count = device.tank_count;

        isEditDialogOpen = true; // Open the modal
    }

    // Fetch devices from the API
    onMount(async () => {});
</script>

<svelte:head>
    <title>Devices</title>
</svelte:head>

<AppLayout {breadcrumbs}>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <Dialog.Root bind:open={isEditDialogOpen}>
            <Dialog.Content>
                <form onsubmit={submitEditDevice} class="space-y-6">
                    <input type="hidden" name="id" bind:value={$editDeviceForm.id} />
                    <Dialog.Header>
                        <Dialog.Title>Edit Device</Dialog.Title>
                        <Dialog.Description>Update the device details below.</Dialog.Description>
                    </Dialog.Header>
                    <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="edit-device-name" class="text-right">Device Name</Label>
                            <Input id="edit-device-name" name="name" bind:value={$editDeviceForm.name} class="col-span-3" />
                        </div>
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="edit-board-id" class="text-right">Board ID</Label>
                            <Input id="edit-board-id" name="board_id" bind:value={$editDeviceForm.board_id} class="col-span-3" />
                        </div>
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="edit-tank-count" class="text-right">Tank Count</Label>
                            <Input id="edit-tank-count" name="tank_count" bind:value={$editDeviceForm.tank_count} class="col-span-3" />
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
            <Card.Header class="flex flex-row items-center">
                <Card.Title class="gap-2">Devices</Card.Title>
                <Dialog.Root bind:open={isAddDialogOpen}>
                    <Dialog.Trigger class="ml-auto gap-1">
                        <Button>
                            <CirclePlus class="h-3.5 w-3.5" />
                            <span class="sr-only sm:not-sr-only sm:whitespace-nowrap"> Add Device </span>
                        </Button></Dialog.Trigger
                    >
                    <Dialog.Content>
                        <form onsubmit={submitAddDevice} class="space-y-6">
                            <Dialog.Header>
                                <Dialog.Title>Add Device</Dialog.Title>
                                <Dialog.Description></Dialog.Description>
                            </Dialog.Header>
                            <div class="grid gap-4 py-4">
                                <div class="grid grid-cols-4 items-center gap-4">
                                    <Label for="device-name" class="text-right">Device Name</Label>
                                    <Input id="device-name" name="name" bind:value={$addDeviceForm.name} class="col-span-3" />
                                </div>
                                <div class="grid grid-cols-4 items-center gap-4">
                                    <Label for="board-id" class="text-right">Board ID</Label>
                                    <Input id="board-id" name="board_id" bind:value={$addDeviceForm.board_id} class="col-span-3" />
                                </div>
                                <div class="grid grid-cols-4 items-center gap-4">
                                    <Label for="tank-count" class="text-right">Tank Count</Label>
                                    <Input id="tank-count" name="tank_count" bind:value={$addDeviceForm.tank_count} class="col-span-3" />
                                </div>
                            </div>
                            <Dialog.Footer>
                                <Button type="submit">Add</Button>
                            </Dialog.Footer>
                        </form>
                    </Dialog.Content>
                </Dialog.Root>
            </Card.Header>
            <Card.Content>
                {#if devices.length === 0}
                    <p>No devices found.</p>
                {:else}
                    <Table.Root>
                        <Table.Header>
                            <Table.Row>
                                <Table.Head class="hidden sm:table-cell">ID</Table.Head>
                                <Table.Head>Device Name</Table.Head>
                                <Table.Head>Board ID</Table.Head>
                                <Table.Head>Tank Count</Table.Head>
                                <Table.Head>Actions</Table.Head>
                            </Table.Row>
                        </Table.Header>
                        <Table.Body>
                            {#each devices as device}
                                <Table.Row>
                                    <Table.Cell class="font-medium">{device.id}</Table.Cell>
                                    <Table.Cell>{device.name}</Table.Cell>
                                    <Table.Cell>BTC-{String(device.board_id).padStart(5, '0')}</Table.Cell>
                                    <Table.Cell>{device.tank_count}</Table.Cell>
                                    <Table.Cell>
                                        <DropdownMenu.Root>
                                            <DropdownMenu.Trigger>
                                                <Button aria-haspopup="true" size="icon" variant="ghost">
                                                    <Ellipsis class="h-4 w-4" />
                                                    <span class="sr-only">Toggle menu</span>
                                                </Button>
                                            </DropdownMenu.Trigger>
                                            <DropdownMenu.Content align="end">
                                                <DropdownMenu.Label>Actions</DropdownMenu.Label>
                                                <DropdownMenu.Item onSelect={() => openEditModal(device)}>Edit</DropdownMenu.Item>
                                                <DropdownMenu.Item onSelect={() => deleteDevice(device.id)}>Delete</DropdownMenu.Item>
                                            </DropdownMenu.Content>
                                        </DropdownMenu.Root>
                                    </Table.Cell>
                                </Table.Row>
                            {/each}
                        </Table.Body>
                    </Table.Root>
                {/if}
            </Card.Content>
            <Card.Footer></Card.Footer>
        </Card.Root>
    </div>
</AppLayout>
