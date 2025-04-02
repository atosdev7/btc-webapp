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
        deviceSelecter?: Snippet;
        children?: Snippet;
    }

    let { breadcrumbs = [], children, deviceSelecter }: Props = $props();
</script>

<AppShell variant="sidebar">
    <AppSidebar />
    <AppContent variant="sidebar">
        {#if deviceSelecter}
            <AppSidebarHeaderWithDeviceList {breadcrumbs} {deviceSelecter} />
        {:else}
            <AppSidebarHeader {breadcrumbs} />
        {/if}
        {@render children?.()}
    </AppContent>
</AppShell>
