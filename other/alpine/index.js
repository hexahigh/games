"use strict";

window.onload = function()
{
    var emulator = window.emulator = new V86Starter({
        wasm_path: "v86.wasm",
        memory_size: 300 * 1024 * 1024,
        vga_memory_size: 16 * 1024 * 1024,
        screen_container: document.getElementById("screen_container"),
        bios: {
            url: "https://cdn-mu-ten.vercel.app/v86/seabios.bin",
        },
        vga_bios: {
            url: "https://cdn-mu-ten.vercel.app/v86/vgabios.bin",
        },
        cdrom: {
            url: "https://dl-cdn.alpinelinux.org/alpine/v3.18/releases/x86/alpine-standard-3.18.3-x86.iso",
        },
        autostart: true,
    });
}