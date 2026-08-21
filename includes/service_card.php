<div class="col-lg-4 col-md-6">

    <div class="service-card"
        
        data-id="<?= $service["service_id"]; ?>"
        
         data-service="<?= htmlspecialchars($service["service_name"]); ?>"

         data-category="<?= htmlspecialchars($service["category"]); ?>">

        <span class="selected-badge">

            <i class="bi bi-check-circle-fill"></i>

            Selected

        </span>

        <div class="service-image">

            <?php

            $image = !empty($service["image"])

                ? "../assets/images/services/" . htmlspecialchars($service["image"])

                : "../assets/images/service-placeholder.png";

            ?>

            <img src="<?= $image; ?>"

                 alt="<?= htmlspecialchars($service["service_name"]); ?>">

        </div>

        <div class="service-content">

            <h3>

                <?= htmlspecialchars($service["service_name"]); ?>

            </h3>

            <p>

                <?= htmlspecialchars($service["description"]); ?>

            </p>

            <div class="service-meta">

                <span>

                    <i class="bi bi-clock"></i>

                    <?php

                    if($service["duration"] >= 60){

                        $hours = floor($service["duration"]/60);

                        $minutes = $service["duration"] % 60;

                        echo $minutes > 0

                            ? "{$hours} hr {$minutes} mins"

                            : "{$hours} hr";

                    }else{

                        echo $service["duration"] . " mins";

                    }

                    ?>

                </span>

                <span>

                    <i class="bi bi-cash-stack"></i>

                    KSh <?= number_format($service["price"]); ?>

                </span>

            </div>

        </div>

    </div>

</div>