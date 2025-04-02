<script lang="ts">
    import AppLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import type { BreadcrumbItemType } from '@/types';
    import type { Snippet } from 'svelte';

    interface Props {
        breadcrumbs?: BreadcrumbItemType[];
        deviceSelecter?: Snippet;
        children?: Snippet;
    }

    let { breadcrumbs = [], deviceSelecter, children }: Props = $props();
</script>

<AppLayout {breadcrumbs} {deviceSelecter}>
    {@render children?.()}
</AppLayout>
