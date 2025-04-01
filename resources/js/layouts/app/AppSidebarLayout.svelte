<script lang="ts">
    import AppContent from '@/components/AppContent.svelte';
    import AppShell from '@/components/AppShell.svelte';
    import AppSidebar from '@/components/AppSidebar.svelte';
    import AppSidebarHeader from '@/components/AppSidebarHeader.svelte';
    import AppSidebarHeaderWithDeviceList from '@/components/AppSidebarHeaderWithDeviceList.svelte';
    import type { BreadcrumbItemType } from '@/types';
    import type { Snippet } from 'svelte';

    interface Props {
        breadcrumbs?: BreadcrumbItemType[];
        showDeviceCombobox?: boolean;
        devices?: { id: number; name: string; board_id: string }[];
        selectedDeviceId?: number | null;
        children?: Snippet;
    }

    let { breadcrumbs = [], children, showDeviceCombobox, devices, selectedDeviceId}: Props = $props();
</script>

<AppShell variant="sidebar">
    <AppSidebar />
    <AppContent variant="sidebar">
        {#if showDeviceCombobox==true}
            <AppSidebarHeaderWithDeviceList
                {breadcrumbs}
                devices={devices}
                selectedDeviceId={selectedDeviceId}
            />
        {:else}
            <AppSidebarHeader {breadcrumbs} />
        {/if}
        {@render children?.()}
    </AppContent>
</AppShell>
