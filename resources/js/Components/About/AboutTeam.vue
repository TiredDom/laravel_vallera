<template>
    <section class="py-24 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-5"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">Meet The Team</h2>
                <p class="text-zinc-300 text-lg">The talented developers behind Vallera</p>
            </div>
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative mb-6 inline-block">
                        <div class="h-48 w-48 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl mx-auto overflow-hidden transform group-hover:scale-105 transition-transform duration-300 shadow-2xl border-4 border-blue-300/30">
                            <img
                                src="/images/developers/Bacalando.jpg"
                                alt="Ginelle Bacalando"
                                class="w-full h-full object-cover"
                                @error="$event.target.style.display='none'; $event.target.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-white font-bold text-4xl\'>GB</div>'"
                            />
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-blue-500 rounded-lg opacity-50 blur-xl"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Ginelle Bacalando</h3>
                    <p class="text-blue-400 font-semibold mb-3">Full Stack Developer</p>
                    <p class="text-zinc-400 text-sm">Crafting seamless user experiences</p>
                </div>
                <div class="group text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative mb-6 inline-block">
                        <div class="h-48 w-48 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl mx-auto overflow-hidden transform group-hover:scale-105 transition-transform duration-300 shadow-2xl border-4 border-emerald-300/30">
                            <img
                                src="/images/developers/Gonzales.jpg"
                                alt="John Dominic Gonzales"
                                class="w-full h-full object-cover"
                                @error="$event.target.style.display='none'; $event.target.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-white font-bold text-4xl\'>JG</div>'"
                            />
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-emerald-500 rounded-lg opacity-50 blur-xl"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">John Dominic Gonzales</h3>
                    <p class="text-emerald-400 font-semibold mb-3">Lead Developer</p>
                    <p class="text-zinc-400 text-sm">Building robust architectures</p>
                </div>
                <div class="group text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative mb-6 inline-block">
                        <div class="h-48 w-48 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl mx-auto overflow-hidden transform group-hover:scale-105 transition-transform duration-300 shadow-2xl border-4 border-purple-300/30">
                            <img
                                src="/images/developers/Principe.jpg"
                                alt="Franz Jethro Principe"
                                class="w-full h-full object-cover"
                                @error="$event.target.style.display='none'; $event.target.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-white font-bold text-4xl\'>FP</div>'"
                            />
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-purple-500 rounded-lg opacity-50 blur-xl"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Franz Jethro Principe</h3>
                    <p class="text-purple-400 font-semibold mb-3">Full Stack Developer</p>
                    <p class="text-zinc-400 text-sm">Creating innovative solutions</p>
                </div>
            </div>
        </div>
    </section>
</template>
