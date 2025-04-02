<script lang="ts">
    import * as Select from '@/components/ui/select/index.js';

    interface Device {
        id: number;
        name: string;
        board_id: number;
        tank_count: number;
    }

    interface Props {
        devices: Device[];
        selectedDeviceId?: number | null;
        onSelect?: (id: number) => void;
    }

    let { devices, selectedDeviceId, onSelect }: Props = $props();

    let triggerContent = $derived(devices.find((device) => device.id == selectedDeviceId)?.name ?? 'Select a device');
</script>

<Select.Root type="single" name="selectedDevice" bind:value={selectedDeviceId} onValueChange={(value) => {
        if (value) {
            selectedDeviceId = Number(value);
            if (onSelect) {
                onSelect(selectedDeviceId);
            }
        }
    }}>
    <Select.Trigger class="w-[180px]">
        {triggerContent}
    </Select.Trigger>
    <Select.Content>
        {#each devices as device}
            <Select.Item value={String(device.id)} label={String(device.board_id)}>{device.name}</Select.Item>
        {/each}
    </Select.Content>
</Select.Root>
