<script lang="ts">
    import Breadcrumbs from '@/components/Breadcrumbs.svelte';
    import { SidebarTrigger } from '@/components/ui/sidebar';
    import type { BreadcrumbItem } from '@/types';
    import type { Snippet } from 'svelte';

    interface Props {
        breadcrumbs?: BreadcrumbItem[];
        deviceSelecter?: Snippet;
    }

    let { breadcrumbs = [], deviceSelecter }: Props = $props();
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
        {#if deviceSelecter}
            {@render deviceSelecter()}
        {/if}
    </div>
</header>
