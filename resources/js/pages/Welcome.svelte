<script lang="ts">
    import { Link, page } from '@inertiajs/svelte';

    let user = $derived($page.props.auth.user);
</script>

<svelte:head>
    <title>Welcome</title>
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
</svelte:head>

<div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
    <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
        <nav class="flex items-center justify-end gap-4">
            {#if user}
                <Link
                    as="button"
                    href={route('dashboard')}
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
            {:else}
                <Link
                    as="button"
                    href={route('login')}
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                >
                    Log in
                </Link>
                <Link
                    as="button"
                    href={route('register')}
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Register
                </Link>
            {/if}
        </nav>
    </header>
    <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
        <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
            <div
                class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-20"
            >
                <h1 class="mb-1 font-medium">Welcome to the Brewery Temperature Controller System</h1>
                <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                    The Brewery Temperature Controller System is an advanced solution designed to give brewers complete control over fermentation conditions. Whether you're managing a single fermenter or an entire cellar, our system ensures optimal temperature stability for every batch.
                </p>
                <ul class="flex gap-3 text-sm leading-normal">
                    <li>
                        <a
                            href="/dashboard"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                        >
                            Get Start
                        </a>
                    </li>
                </ul>
            </div>
            <div
                class="relative -mb-px aspect-335/376 w-full shrink-0 overflow-hidden rounded-t-lg bg-[#fff2f2] dark:bg-[#1D0002] lg:-ml-px lg:mb-0 lg:aspect-auto lg:w-[438px] lg:rounded-r-lg lg:rounded-t-none"
            >
                <img src="/btc-splash.jpg" />
                <div
                    class="absolute inset-0 rounded-t-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:overflow-hidden lg:rounded-r-lg lg:rounded-t-none"
                ></div>
            </div>
        </main>
    </div>
    <div class="h-14.5 hidden lg:block"></div>
</div>
