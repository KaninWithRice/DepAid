<?php include 'includes/header.php'; ?>

<main class="flex-grow py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 shadow-sm p-6">
            <div class="text-sm text-gray-500 mb-8">
                <a href="index.php?page=masterlist" class="text-blue-600 hover:underline">Masterlist</a>
                <span class="mx-2">/</span>
                <a href="index.php?page=masterlist" class="text-blue-600 hover:underline"><?php echo $selectedClass; ?></a>
                <span class="mx-2">/</span>
                <span>Enrolment</span>
            </div>

            <div class="max-w-xl mx-auto text-center py-12">
                <h2 class="text-xl text-gray-800 mb-4"><?php echo explode(' – ', $selectedClass)[0]; ?> Enrolment</h2>
                <p class="text-gray-600 text-sm leading-6 mb-6">
                    Use applicable documents as source to ensure accuracy of this enrolment transaction.
                </p>
                <ul class="text-gray-600 text-sm list-disc list-inside inline-block text-left mb-8">
                    <li>NSO/Birth/Baptismal certificate</li>
                    <li>Form 137/138</li>
                </ul>
                <div>
                     <a href="index.php?page=search" class="bg-[#5cb85c] text-white px-6 py-2 rounded-md hover:bg-[#4cae4c] font-medium text-sm inline-block">Proceed Enrolment</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>