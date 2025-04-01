<script lang="ts">
    import Breadcrumbs from '@/components/Breadcrumbs.svelte';
    import { SidebarTrigger } from '@/components/ui/sidebar';
    import type { BreadcrumbItem } from '@/types';
    import * as Popover from '@/components/ui/popover/index.js';
    import * as Command from '@/components/ui/command';
    import { Button } from '@/components/ui/button';
    import Check from 'lucide-svelte/icons/check';
    import ChevronsUpDown from 'lucide-svelte/icons/chevrons-up-down';
    import { cn } from '@/lib/utils';
    import { tick } from 'svelte';
    
    interface Props {
        breadcrumbs?: BreadcrumbItem[];
        devices?: { id: number; name: string; board_id: number }[];
        selectedDeviceId?: number | null;
    }

    let { breadcrumbs = [], devices = [], selectedDeviceId = null }: Props = $props();

    let isDeviceComboboxOpen = true;
    let selectedDevice = $derived(devices.find(device => device.id == selectedDeviceId));
    
    function closeAndFocusTrigger(triggerId: string) {
        isDeviceComboboxOpen = false;
        tick().then(() => {
            document.getElementById(triggerId)?.focus();
        });
    }

</script>

<header
    class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
>
    <div class="flex items-center gap-2 justify-between w-full">
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />

            {#if breadcrumbs.length > 0}
                <Breadcrumbs {breadcrumbs} />
            {/if}
        </div>

        {#if devices.length > 0}
            <Popover.Root>
                <Popover.Trigger>
                    <Button variant="outline" role="combobox" aria-expanded={isDeviceComboboxOpen} class="w-[150px] justify-between">
                        {selectedDevice?.name || "Select Device"}
                        <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                    </Button>
                </Popover.Trigger>
                <Popover.Content class="w-[150px] p-0 right-0">
                    <Command.Root>
                        <Command.Group>
                            {#each devices as device}
                                <Command.Item value={String(device.id)}>
                                    <Check class={cn('mr-2 h-4 w-4', selectedDeviceId != device.id && 'text-transparent')} />
                                    {device.name}
                                </Command.Item>
                            {/each}
                        </Command.Group>
                    </Command.Root>
                </Popover.Content>
            </Popover.Root>
        {/if}
    </div>
</header>
