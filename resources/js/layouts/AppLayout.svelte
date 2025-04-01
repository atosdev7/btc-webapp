<script lang="ts">
    import AppLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import type { BreadcrumbItemType } from '@/types';
    import type { Snippet } from 'svelte';

    interface Props {
        breadcrumbs?: BreadcrumbItemType[];
        showDeviceCombobox?: boolean;
        devices?: { id: number; name: string; board_id: string }[];
        selectedDeviceId?: number | null;        
        children?: Snippet;
    }

    let { breadcrumbs = [], showDeviceCombobox, devices, selectedDeviceId, children }: Props = $props();
</script>

<AppLayout {breadcrumbs} showDeviceCombobox={showDeviceCombobox} devices={devices} selectedDeviceId={selectedDeviceId}>
    {@render children?.()}
</AppLayout>
