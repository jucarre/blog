<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerC977Wxw\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerC977Wxw/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerC977Wxw.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerC977Wxw\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerC977Wxw\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'C977Wxw',
    'container.build_id' => '5e14abeb',
    'container.build_time' => 1553700159,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerC977Wxw');
